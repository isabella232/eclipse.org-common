<?php
/*******************************************************************************
 * Copyright (c) 2007-2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    Christopher Guindon (Eclipse Foundation) - Bug 440590 - Improve the flexibility of session.class.php
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../classes/friends/friend.class.php"));
require_once("app.class.php");
if (!class_exists("EvtLog")) {
  require_once("evt_log.class.php");
}

class Session {

  private $App = NULL;

  private $gid = "";

  private $bugzilla_id = 0;

  private $subnet = "";

  private $updated_at = "";

  private $Friend = NULL;

  private $data = "";

  private $session_name = "";

  private $domain = "";

  private $env = "";

  private $login_page = "";

  /**
   * Default constructor
   *
   * @return NULL
   */
  public function __construct($persistent=0, $configs = array()) {
   $this->App = new App();
   $domain = $this->App->getEclipseDomain();
   $default = array(
      'domain' => $domain['cookie'] ,
      'session_name' => 'ECLIPSESESSION',
      'env' => 'ECLIPSE_ENV',
      'login_page' => 'https://' . $domain['accounts'] . '/user/login',
    );

    # Set default config values.
    foreach ($default as $key => $value) {
      $this->{$key} = $value;
      if (!empty($configs[$key]) && is_string($configs[$key])) {
        $this->{$key} = $configs[$key];
      }
    }

    $this->validate();
  }

  function getGID() {
    return $this->gid;
  }

  function getBugzillaID() {
    return $this->bugzilla_id;
  }

  function getSubnet() {
    return $this->subnet;
  }

  function getUpdatedAt() {
    return $this->updated_at;
  }

  function getFriend() {
    if($this->Friend == NULL) {
      $this->Friend = new Friend();
    }
    return $this->Friend;
  }

  function getData() {
    return unserialize($this->data);
  }

  function getIsPersistent() {
    if ($this->hasCookieConsent()) {
      return 1;
    }
    return 0;
  }

  function getLoginPageURL() {
    return $this->login_page;
  }

  function getIsLoggedIn() {
    return $this->getGID() !== "";
  }

  /**
   * Verify if consent was given to use cookies
   *
   * @return boolean
   */
  public function hasCookieConsent() {
    $App = new App();
    return $App->hasCookieConsent();
  }


  function setGID($_gid) {
    $this->gid = $_gid;
  }

  function setBugzillaID($_bugzilla_id) {
    if (ctype_digit($_bugzilla_id)) {
      $this->bugzilla_id = $_bugzilla_id;
    }
  }

  function setSubnet($_subnet) {
    $this->subnet = $_subnet;
  }

  function setUpdatedAt($_updated_at) {
    $this->updated_at = $_updated_at;
  }

  function setFriend($_friend) {
    $this->Friend = $_friend;
  }

  function setData($_data) {
    $this->data = serialize($_data);
  }

  /**
   * Set is_persistent
   *
   * @deprecated
   */
  function setIsPersistent($_is_persistent) {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
  }

  /**
   * Validate session based on browser cookie
   *
   * @return boolean
   */
  function validate() {
    $cookie = (isset($_COOKIE[$this->session_name]) ? $_COOKIE[$this->session_name] : "");
    $rValue = FALSE;
    if ($this->load($cookie)) {
      # TODO: update session?
      $rValue = TRUE;
      $this->maintenance();
      $this->setFriend($this->getData());
    }
    return $rValue;
  }

  function destroy($flush_all_sessions = FALSE) {
    $App = new App();
    $Friend = $this->getFriend();

    if ($flush_all_sessions && $Friend->getBugzillaID() > 0) {
      $sql = "DELETE FROM sessions WHERE bugzilla_id = '" . $App->sqlSanitize($Friend->getBugzillaID(), 0) . "'";
    }
    else {
      $sql = "DELETE FROM sessions WHERE gid = '" . $App->sqlSanitize($this->getGID(), NULL) . "' LIMIT 1";
    }
    $App->eclipse_sql($sql);

    # Remove the TAKEMEBACK cookie
    # Should these also be in session() ?
    setcookie("TAKEMEBACK", "", 0, "/", ".eclipse.org");
    setcookie("fud_session_2015", "", 0, "/forums/", ".eclipse.org");
    setcookie($this->session_name, "", 0, "/", $this->domain, 1, TRUE);
    setcookie($this->env, "", 0, "/", $this->domain, 0, TRUE);

    if (!$App->devmode) {
      # Log this event
      $EvtLog = new EvtLog();
      $EvtLog->setLogTable("sessions");
      $EvtLog->setPK1($Friend->getBugzillaID());
      $EvtLog->setPK2($_SERVER['REMOTE_ADDR']);
      $EvtLog->setLogAction("DELETE");
      $EvtLog->insertModLog("apache");
    }
  }

  function create() {
    # create session in the database.
    $Friend = $this->getFriend();
    $this->setData($Friend);

    # need to have a LDAP ID to log in.
    if ($Friend->getUID()) {
      $App = new App();
      $this->setGID(md5(uniqid(rand(), TRUE)));
      $this->setSubnet($this->getClientSubnet());
      $this->setUpdatedAt($App->getCURDATE());

      // Bugzilla id is missing, let's try to find it.
      if (!$Friend->getBugzillaID() && $Friend->getEmail()) {
        $Friend->setBugzillaID($Friend->getBugzillaIDFromEmail($Friend->getEmail()));
      }

      $this->setBugzillaID($Friend->getBugzillaID());
      //$Friend->insertUpdateFriend();

      $sql = "INSERT INTO sessions (
            gid,
            bugzilla_id,
            subnet,
            updated_at,
            data,
            is_persistent)
            VALUES (
              " . $App->returnQuotedString($this->getGID()) . ",
              " . $App->sqlSanitize($Friend->getBugzillaID(), NULL) . ",
              " . $App->returnQuotedString($this->getSubnet()) . ",
              NOW(),
              '" . $App->sqlSanitize($this->data) . "',
              '" . $App->sqlSanitize($this->getIsPersistent(), NULL) . "')";

      $App->eclipse_sql($sql);

      if (!$App->devmode) {
        # Log this event
        $EvtLog = new EvtLog();
        $EvtLog->setLogTable("sessions");
        $EvtLog->setPK1($Friend->getBugzillaID());
        $EvtLog->setPK2($_SERVER['REMOTE_ADDR']);
        $EvtLog->setLogAction("INSERT");
        $EvtLog->insertModLog("apache");
      }
      $this->setEclipseSessionCookies();
    }
  }

  /**
   * Set Eclipse Session Cookies
   *
   * @return boolean
   */
  public function setEclipseSessionCookies(){
    $gid = $this->getGID();
    if (empty($gid)) {
      return FALSE;
    }

    $cookie_time = 0;
    if ($this->getIsPersistent()) {
      $cookie_time = time()+3600*24*7;
    }

    setcookie($this->session_name, $this->getGID(), $cookie_time, "/", $this->domain, 1, TRUE);
    # 422767 Session broken between http and https
    # Set to "S" for Secure.  We could eventually append more environment data, separated by semicolons and such
    setcookie($this->env, "S", $cookie_time, "/", $this->domain, 0, TRUE);
    return TRUE;
  }

  function load($_gid) {
    # need to have a bugzilla ID to log in
    $rValue = FALSE;
    if (!empty($_gid)) {
      $App = new App();
      $sql = "SELECT /* USE MASTER */ gid, bugzilla_id, subnet, updated_at, data,  is_persistent
        FROM sessions
        WHERE gid = " . $App->returnQuotedString($App->sqlSanitize($_gid, NULL));
        # " AND subnet = " . $App->returnQuotedString($this->getClientSubnet());

      $result = $App->eclipse_sql($sql);
      if ($result && mysql_num_rows($result) > 0) {
        $rValue = TRUE;
        $myrow = mysql_fetch_assoc($result);
        $this->setGID($_gid);
        $this->setBugzillaID($myrow['bugzilla_id']);
        $this->setSubnet($myrow['subnet']);
        $this->setUpdatedAt($myrow['updated_at']);
        $this->data = $myrow['data'];

        # touch this session
        $sql = "UPDATE sessions SET updated_at = NOW(), is_persistent = '" . $App->sqlSanitize($this->getIsPersistent(), NULL) . "' WHERE gid = '" . $App->sqlSanitize($_gid, NULL) . "'";
        $App->eclipse_sql($sql);
        $this->setEclipseSessionCookies();
      }
    }
    return $rValue;
  }

  function maintenance() {
    $App = new App();
    // Sessions are re-generated by visiting accounts.eclipse.org
    $sql = "DELETE FROM sessions WHERE updated_at < DATE_SUB(NOW(), INTERVAL 8 DAY)";
    $App->eclipse_sql($sql);
  }

  function getClientSubnet() {
    # return class-c subnet
    return substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], ".")) . ".0";
  }

  function redirectToLogin() {
    $this->App->preventCaching();
    header("Location: " . $this->login_page, 303);
    exit;
  }

  /**
   * Determine if this session is logged in.
   * @author droy
   * @since 2014-07-03
   * @return boolean
   */
  function isLoggedIn() {
    return $this->getGID() != "";
  }

  /**
   * Update Friend object in Sessions table.
   *
   * @param object $Friend
   * @return boolean
   */
  function updateSessionData($Friend = NULL) {
    if (is_null($Friend)) {
      $Friend = $this->getFriend();
    }

   if (is_a($Friend, 'Friend') && $gid = $this->getGID()) {
      $this->setFriend($Friend);
      $this->setData($Friend);

      $sql = "UPDATE sessions SET updated_at = NOW(),
        data = '" . $this->App->sqlSanitize($this->data) . "'
        WHERE gid = '" . $this->App->sqlSanitize($gid, NULL) . "'";
       $this->App->eclipse_sql($sql);
       return TRUE;
    }

    return FALSE;
  }
}