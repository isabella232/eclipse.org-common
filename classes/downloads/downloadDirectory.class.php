<?php
/**
 * Copyright (c) 2020 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/session.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php");

class DownloadDirectory {

  /**
   * App object
   */
  private $App;

  /**
   * Processing paths
   */
  private $processing_paths = array();

  /**
   *
   * The directory to work with
   */
 private $basedir = "";

  /**
   * Person ID
   */
  private $person_id = "";

  /**
   * LDAP Group
   */
  private $ldap_group = NULL;

  /**
   * User is committer
   */
  private $user_is_committer = NULL;

  private $request_uri = "";

  public function __construct() {
    $this->App = new App();

  }

  /**
   * Get current path
   *
   * @param string $file
   *
   * @return string
   */
  private function _getPath($file) {
    if (empty($file)) {
      return "";
    }

    $file = $this->App->checkPlain($file);
    $current_dir_path = $this->_getUri();

    // Remove the last directory from the path
    $current_dir_path_array = explode('/', $current_dir_path);
    $count = count($current_dir_path_array);
    unset($current_dir_path_array[$count - 1]);

    // Scan through the parent's current directory to find out
    // the proper way to write the current directory.
    $parent_path = implode('/', $current_dir_path_array);
    $parent_path = rtrim($parent_path, '/') . "/";

    // Validate if we're dealing with an existing file or directory
    $file_path = $parent_path . $file;
    if (is_file($this->getCurrentDirectory() . $file) || is_dir($this->getCurrentDirectory() . $file)) {
      return $file_path;
    }

    return "";
  }

  /**
   * Get the output HTML of a file
   *
   * @return string
   */
  private function _getFileOutput($file) {

    if (empty($file)) {
      return "";
    }

    $path = $this->_getPath($file);
    $processing_paths = $this->_getProcessingRequests();

    $input_disabled = '';
    $suffix_text = '';
    if (array_key_exists($path, $processing_paths)) {
      $action = 'archived';
      if ($this->_isArchiveDomain()) {
        $action = 'deleted';
      }
      $suffix_text = '<span class="small">(This file is being ' . $action . ')</span>';

      # examine the token (return value from the handler
      if(!empty($processing_paths[$path])) {
          $suffix_text = '<span class="small">(File cannot be ' . $action . ': ' . $processing_paths[$path] . '. Please contact webmaster.)</span>';
      }
      $input_disabled = 'disabled="disabled"';
    }

    $link = "<img src='//dev.eclipse.org/small_icons/actions/edit-copy.png'><a href='" . $path . "'> " . $file . "</a>";
    if (empty($path) || !$this->_userIsCommitterOnProject()) {
      return '<p>'.$link.'</p>';
    }
    return '<p><input ' . $input_disabled . ' type="checkbox" name="paths_to_archive[]" value="'. $path .'"> - ' . $link . ' ' . $suffix_text . '</p>';
  }

  /**
   * Get the output HTML of a folder
   *
   * @return string
   */
  private function _getFolderOutput($directory) {

    if($directory === ".") {
      return "";
    }

    $path = $this->_getPath($directory);
    $processing_paths = $this->_getProcessingRequests();

    $input_disabled = '';
    $suffix_text = '';
    if (array_key_exists($path, $processing_paths)) {
      $action = 'archived';
      if ($this->_isArchiveDomain()) {
        $action = 'deleted';
      }
      $suffix_text = '<span class="small">(This folder is being ' . $action . ')</span>';

      # examine the token (return value from the handler
      if(!empty($processing_paths[$path])) {
        $suffix_text = '<span class="small">(Folder cannot be ' . $action . ': ' . $processing_paths[$path] . '. Please contact webmaster.)</span>';
      }
      $input_disabled = 'disabled="disabled"';
    }

    $link = "<img src='//dev.eclipse.org/small_icons/places/folder.png'><a href='" . $path . "'> " . $directory . "</a> " . $suffix_text;

    if (empty($path) || $directory === ".." || !$this->_userIsCommitterOnProject()) {
      return '<p>'.$link.'</p>';
    }

    return '<p><input ' . $input_disabled . ' type="checkbox" name="paths_to_archive[]" value="'. $path .'"> - ' . $link . '</p>';
  }

  /**
   * Get Person ID from Session
   *
   * @return string
   */
  private function _getPersonID() {
    if (empty($this->person_id)) {
      $Session = new Session();
      $Friend = $Session->getFriend();
      $this->person_id = $Friend->getUID();
    }
    return $this->person_id;
  }

  /**
   * Get all the processing requests from account_requests table
   *
   * @return array
   */
  private function _getProcessingRequests() {

    if (!empty($this->processing_paths)) {
      return $this->processing_paths;
    }

    $action = "DOWNLOAD_ARCHIVE";
    if ($this->_isArchiveDomain()) {
      $action = "DOWNLOAD_DELETE";
    }

    $sql = "SELECT password as Path, token
      FROM account_requests
      WHERE fname = " . $this->App->returnQuotedString($this->App->sqlSanitize($action)) . "
      AND lname = " . $this->App->returnQuotedString($this->App->sqlSanitize($action));
    $result = $this->App->eclipse_sql($sql);

    if (empty($result)) {
      $this->processing_paths;
    }

    while($myrow = mysql_fetch_array($result)) {
      $this->processing_paths[$myrow['Path']] = $myrow['token'];
    }
    return $this->processing_paths;
  }

  /**
   * Get uri
   *
   * @return string
   */
  private function _getUri() {
    if (empty($this->request_uri)) {
      $this->request_uri = $_SERVER['REQUEST_URI'];
    }
    return str_replace("?d", "", $this->request_uri);
  }

  /**
   * Get the url of the current directory
   *
   * @return string
   */
  public function getCurrentDirectory() {
    return $_SERVER['DOCUMENT_ROOT'] . urldecode($this->_getUri());
  }

  /**
   * Get the project ID based on the group owner of the folder
   *
   * @return string
   */
  private function _getProjectID() {
    $group = $this->getLdapGroupByGid(filegroup($this->basedir));

    if (empty($group)) {
      return "";
    }
    return $group;
  }

  /**
   * Insert into account_requests table
   *
   * @return bool
   */
  private function _insertRequest() {
    if (empty($_POST['paths_to_archive'])) {
      return FALSE;
    }

    $action = "DOWNLOAD_ARCHIVE";
    if ($this->_isArchiveDomain()) {
      $action = "DOWNLOAD_DELETE";
    }

    foreach ($_POST['paths_to_archive'] as $path) {
      $sql = "SELECT
              email
              FROM account_requests
              WHERE email = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_getPersonID())) . "
              AND password = ". $this->App->returnQuotedString($this->App->sqlSanitize($path));
      $result = $this->App->eclipse_sql($sql);
      while ($row = mysql_fetch_array($result)) {
        if (!empty($row['email'])) {
          return FALSE;
        }
      }

      $sql = "INSERT INTO account_requests (
          email,
          new_email,
          fname,
          lname,
          password,
          ip,
          req_when,
          token
        )
          VALUES (
          " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_getPersonID())) . ",
          NULL,
          " . $this->App->returnQuotedString($this->App->sqlSanitize($action)) . ",
          " . $this->App->returnQuotedString($this->App->sqlSanitize($action)) . ",
          " . $this->App->returnQuotedString($this->App->sqlSanitize($path)) . ",
          " . $this->App->returnQuotedString($this->App->sqlSanitize($this->App->getRemoteIPAddress())) . ",
          NOW(),
          NULL
          )";
      $this->App->eclipse_sql($sql);
    }
  }

  /**
   * Check if the current domain is archive.eclipse.org
   *
   * @return bool
   */
  private function _isArchiveDomain() {
    if (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'archive.eclipse.org') !== FALSE) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if the user is a committer on a specific project
   *
   * @return bool
   */
  private function _userIsCommitterOnProject() {

    if (!is_null($this->user_is_committer)) {
      return $this->user_is_committer;
    }

    $sql = "SELECT count(1) as count
      FROM PeopleProjects
      WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_getPersonID())) . "
      AND ProjectID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_getProjectID())) . "
      AND Relation = " . $this->App->returnQuotedString("CM") . "
      AND (InactiveDate IS NULL OR InactiveDate = '0000-00-00')";
    $result = $this->App->foundation_sql($sql);
    $this->user_is_committer = FALSE;
    while($myrow = mysql_fetch_array($result)) {
      if ($myrow['count']) {
        $this->user_is_committer = TRUE;
      }
    }

    return $this->user_is_committer;
  }



  /**
   * Get the Form ouput
   *
   * @return string
   */
  public function getFormOutput($files, $dirs, $basedir=null) {

    if($basedir === null) {
      $basedir = $this->getCurrentDirectory();
    }
    $this->basedir = $basedir;

    $output = "";
    $html_checkboxes = array();
    foreach ($dirs as $directory) {
      if($directory === ".") {
        continue;
      }

      if ($directory === ".." || $this->_userIsCommitterOnProject() === FALSE) {
        $output .= $this->_getFolderOutput($directory);
        continue;
      }

      $html_checkboxes[] = $this->_getFolderOutput($directory);
    }

    foreach ($files as $file) {
      if ($this->_userIsCommitterOnProject() === FALSE) {
        $output .= $this->_getFileOutput($file);
      }
      $html_checkboxes[] = $this->_getFileOutput($file);
    }

    if ($this->_userIsCommitterOnProject() === FALSE) {
      return $output;
    }

    // Now that we know that the current user is a committer for this project,
    // we can safely insert a new request on page reload
    $this->_insertRequest();

    $output .= '<form class="downloads-directory" method="post" action="/errors/filehandler.php">';
    $output .= implode("", $html_checkboxes);

    $button_text = 'Archive';
    $button_class = 'btn-primary';
    if ($this->_isArchiveDomain()) {
      $button_text = 'Delete';
      $button_class = 'btn-danger';
    }

    $output .= '<input disabled id="deletesubmit" class="btn btn-xs ' . $button_class . '" type="submit" value="' . $button_text . '" />';
    $output .= "</form>";
    $output .= "<span class='small'>File and folder operations make take several seconds to complete. Refresh the page to get current status.</span>";
    return $output;
  }

  /**
   * Get the LDAP Group name, by gid
   *
   * @return string
   */
  public function getLdapGroupByGid($gid) {
    if (!is_null($this->ldap_group)) {
      return $this->ldap_group;
    }
    $LDAPConnection = new LDAPConnection();
    $this->ldap_group = $LDAPConnection->getGroupByGid($gid);
    return $this->ldap_group;
  }
}
