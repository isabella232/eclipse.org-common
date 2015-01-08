<?php
/*******************************************************************************
 * Copyright (c) 2012-2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

/**
 * Usage example:
 *
 * $AccountCreator = New AccountCreator();
 * $AccountCreator->setDebugMode();
 * $AccountCreator->setUrl('https://bugs.eclipse.org/bugstest/index.cgi');
 * $AccountCreator->setUsername('user@mail.com');
 * $AccountCreator->setPassword('the_password');
 * $AccountCreator->setAccountType('gerrit');
 * $AccountCreator->execute();
 */


/**
 * Eclipse Account Creator Class
 *
 * Create new users to 3rd party applications.
 *
 * @package Site_login
 * @author Christopher Guindon
 */
class AccountCreator {

  /**
   * Type of Account to create
   *
   * @var string
   */
  private $account_type = "";

  /**
   * Enable or disable debug mode.
   *
   * @var bool
   */
  private $debug = FALSE;

  /**
   * Username/e-mail address of the user.
   *
   * @var string
   */
  private $username = "";

  /**
   * Password of the user.
   *
   * @var string
   */
  private $password = "";

  /**
   * Url of Website.
   *
   * @var string
   */
  private $url = "";

  // --------------------------------------------------------------------

  /**
   * Constructor - Sets default settings
   *
   * @return void
   */
  function __construct() {
    $this->url = "https://bugs.eclipse.org/bugs/index.cgi";
  }

  /**
   * Execute Login Process
   *
   * @return int/bool
   */
  public function execute() {
    if (filter_var($this->username, FILTER_VALIDATE_EMAIL) && !empty($this->password)) {
      return $this->_process();
    }
    else{
      trigger_error("Invalid username or password", E_USER_NOTICE);
    }
    return FALSE;
  }

  /**
   * Set Account Type
   *
   * @return bool
   */
  public function setAccountType($type = "") {
    $allowed_type = array('gerrit', 'bugzilla');
    $type = strtolower($type);
    if (in_array($type, $allowed_type)) {
      $this->account_type = $type;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Enable Debug Mode
   *
   * @return bool
   */
  public function setDebugMode($set = TRUE){
    if ($set == TRUE) {
      $this->debug = TRUE;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set Password
   *
   * @return bool
   */
  public function setPassword($password = "") {
    if (!empty($password)) {
      $this->password = $password;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set Website URL
   *
   * @return bool
   */
  public function setUrl($url = "") {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->url = $url;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set Username
   *
   * @return bool
   */
  public function setUsername($username = "") {
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
      $this->username = $username;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Print Response Output
   *
   * @return int
   */
  private function _output($ch){

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
      // @todo: Log errors
      if ($this->debug) {
        echo 'Error: ' . curl_error($ch);
      }
    }
    else {
      if ($this->debug) {
        print $result;
      }
    }
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $http_code;
  }

  /**
   * Initialize a CURL Session
   *
   * @return int
   */
  private function _process() {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (site_login)");

    // Bug 442432 - New posts are being associated with incorrect accounts/authors
    curl_setopt($ch, CURLOPT_REFERER, $this->url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

    switch ($this->account_type) {
      case "gerrit":
        $post = "username=" . urlencode($this->username) . "&password=" . urlencode($this->password);
        break;

      default:
        $post = "Bugzilla_login=" . urlencode($this->username) . "&Bugzilla_password=" . urlencode($this->password);
        break;
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    return $this->_output($ch);
  }

}
