<?php
/*******************************************************************************
* Copyright (c) 2016 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Christopher Guindon (Eclipse Foundation) - Initial implementation
*******************************************************************************/
require_once('restclient.class.php');

/**
 * EclipseUSSBlob class
 *
 * @author chrisguindon
 */
class EclipseUSSBlob extends RestClient{

  protected $authenticated = FALSE;

  protected $User = NULL;

  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->setBaseUrl('https://api.eclipse.org/api');

    switch ($this->getEnvShortName()) {
      case 'local':
        $this->setBaseUrl('https://api.eclipse.local:51243/api');
        break;
      case 'staging':
        $this->setBaseUrl('https://api-staging.eclipse.org/api');
        break;
    }
    $this->getUser();
    $this->_setAuthHeaders();
  }

  /**
   * Login to Eclipse USS
   *
   * @param string $username
   * @param string $password
   *
   * @return Response $data
   */
  public function login($username = "", $password = ""){
    $data = array(
      'username' => $username,
      'password' => $password,
    );
    $json = json_encode($data);
    $data = $this->post('user/login', $json);
    if (!isset($data->error) && !empty($data->body) && $data->code == '200') {
      $this->User = json_decode($data->body);
      $this->_setAuthHeaders();
      return TRUE;
    }
    return FALSE;
  }


  /**
   * Login to Eclipse USS with Eclipse Session cookie
   *
   * @return Response $data
   */
  public function loginSSO() {
    if ($this->isAuthenticated()) {
      return TRUE;
    }

    $cookie = (isset($_COOKIE['ECLIPSESESSION']) ? $_COOKIE['ECLIPSESESSION'] : "");
    if (empty($cookie)){
      return FALSE;
    }

    require_once(realpath(dirname(__FILE__) . '/../../../system/session.class.php'));
    $Session = new Session();
    $Friend = $Session->getFriend();
    $email = $Friend->getEmail();
    if (empty($email)) {
      return FALSE;
    }
    $data = array(
      'username' => $email,
      'session' => $cookie,
    );
    $json = json_encode($data);
    $data = $this->post('user/loginsso', $json);

    if (!isset($data->error) && !empty($data->body) && $data->code == '200') {
      $this->User = json_decode($data->body);
      $this->_setAuthHeaders();
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get Blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   *
   * @return Response $data
   */
  public function getBlob($application_token = "", $blob_key = "", $etag = "") {
    if (!empty($etag)) {
      $this->setHeader(array(
        'If-None-Match' => $etag,
      ));
    }
    $data = $this->get('blob/' . $application_token . '/' . $blob_key);
    if ($this->_loginIfUnAuthorized($data)) {
      $data = $this->get('blob/' . $application_token . '/' . $blob_key);
    }
    $this->unsetHeader('If-None-Match');
    return $data;
  }

  /**
   * Get an index of blobs
   *
   * @param string $application_token
   * @param number $page
   * @param number $pagesize
   *
   * @return Response $data
   */
  public function indexBlob($application_token = "", $page = 1, $pagesize = 20) {
    $url = 'blob/' . $application_token . '?page=' . $page . '&pagesize=' . $pagesize;
    $data = $this->get($url);
    if ($this->_loginIfUnAuthorized($data)) {
      $data = $this->get($url);
    }
    return $data;
  }

  /**
   * Fetch all blob from an $application_token
   *
   * @param string $application_token
   * @param number $page
   * @param number $pagesize
   *
   * @return Response $data
   */
  public function indexAllBlob($application_token = "", $page = 1, $pagesize = 20) {
    $url = 'blob/' . $application_token . '?page=' . $page . '&pagesize=' . $pagesize;
    $data = $this->get($url);
    if ($this->_loginIfUnAuthorized($data)) {
      $data = $this->get($url);
    }

    $pages = $this->_getHeaderLink($data->headers['Link']);
    $return = array();
    $return[] = $data;
    if (!isset($data->error) && !empty($data->body) && $data) {
      while ($data = $this->_getNextPage($data)) {
        $return[] = $data;
      }
    }
    return $return;
  }

  /**
   * Create or update a blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   * @param unknown $data
   *
   * @return Response $data
   */
  public function putBlob($application_token = "", $blob_key = "", $etag = "", $data = NULL) {
    $fields['value'] = base64_encode($data);
    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag,
      ));
    }
    $result = $this->put('blob/' . $application_token . '/' . $blob_key, json_encode($fields));
    if ($this->_loginIfUnAuthorized($result)) {
      $result = $this->put('blob/' . $application_token . '/' . $blob_key, json_encode($fields));
    }
    $this->unsetHeader('If-Match');
    return $result;
  }

  /**
   * Delete a blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   *
   * @return Response $data
   */
  public function deleteBlob($application_token = "", $blob_key = "", $etag = "") {
    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag,
      ));
    }
    $data = $this->delete('blob/' . $application_token . '/' . $blob_key);
    if ($this->_loginIfUnAuthorized($data)) {
      $data = $this->delete('blob/' . $application_token . '/' . $blob_key);
    }
    $this->unsetHeader('If-Match');
    return $data;
  }

  /**
   * Destroy user session
   *
   * @return Response $data
   */
  public function logout() {
    $data = $this->post('user/logout');
    if (!isset($data->error) && $data->code == '200') {
      $this->authenticated = FALSE;
      $this->User = NULL;
      if (isset($_SESSION['eclipseussblob']['user'])) {
        unset($_SESSION['eclipseussblob']['user']);
      }
    }
    return $data;
  }

  /**
   * Verify if the user is currently logged in
   *
   * @return bool
   */
  public function isAuthenticated() {
    return $this->authenticated;
  }

  /**
   * Get $user
   *
   * @return stdClass $user
   */
  public function getUser(){
    if (!empty($_SESSION['eclipseussblob']['user'])) {
      $this->User = $_SESSION['eclipseussblob']['user'];
    }
    return $this->User;
  }

  /**
   * Set Auth Headers()
   */
  private function _setAuthHeaders() {
    if ($this->User) {
      $this->authenticated = TRUE;
      $session_cookie[$this->User->session_name] = $this->User->session_name . '=' . $this->User->sessid;
      $this->setCookie($session_cookie);
      $this->setHeader(array(
        'X-CSRF-Token' => $this->User->token,
      ));
      $_SESSION['eclipseussblob']['user'] = $this->User;
    }
  }

  /**
   * Try to login if response code is 401
   * @param stdClass $data
   */
  private function _loginIfUnAuthorized($data) {
    if ($data->code == '401') {

      $this->authenticated = FALSE;
      $this->User = NULL;

      if ($this->loginSSO()) {
        return TRUE;
      }
    }
    return FALSE;
  }

  protected function _errorConflict(){
    return array(
      'code' => '409',
      'status_message' => 'Conflict',
      'body' => NULL,
    );
  }

  protected function _errorNotLoggedIn() {
    return array(
        'code' => '403',
        'status_message' => 'Unable to login',
        'body' => NULL,
      );
  }

  protected function _errorBadRequest($fieldname = "", $type = "missing") {
    return array(
        'code' => '400',
        'status_message' => ucfirst($type) . ' (' . $fieldname . ')',
        'body' => NULL,
      );
  }
}