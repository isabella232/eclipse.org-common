<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

class Subscriptions_base {

  protected $App = NULL;

  private $debug_mode = FALSE;

  private $email = "";

  private $first_name = "";

  private $last_name = "";

  protected $Friend = NULL;

  protected $Sessions = NULL;

  function __construct(App $App) {
    $this->App = $App;
    $this->Sessions = $this->App->useSession();
    $this->Friend = $this->Sessions->getFriend();

    // Check to see if we're on staging or on production
    if ($this->App->getEclipseDomain() != 'www.eclipse.org') {
      $this->_setDebugMode(TRUE);
    }
  }

  /**
   * Get First Name
   */
  public function getFirstName() {
    if (empty($this->first_name)) {
      $this->setFirstName($this->Friend->getFirstName());
    }
    return $this->first_name;
  }

  /**
   * Set First Name
   *
   * @param string $first_name
   */
  public function setFirstName($first_name = "") {
    $this->first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    return $this->first_name;
  }

  /**
   * Get Last Name
   */
  public function getLastName() {
    if (empty($this->last_name)) {
      $this->setLastName($this->Friend->getLastName());
    }
    return $this->last_name;
  }

  /**
   * Set Last Name
   *
   * @param string $last_name
   */
  public function setLastName($last_name = ""){
    $this->last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
    return $this->first_name;
  }

  /**
   * Get Email
   */
  public function getEmail() {
    if (empty($this->email)) {
      $this->email = $this->setEmail($this->Friend->getEmail());
    }
    return $this->email;
  }

  /**
   * Set Email
   *
   * @param string $email
   */
  public function setEmail($email = "") {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->email = $email;
    }

    return $this->email;
  }

  /**
   * Get debug mode value
   *
   * @return Ambigous <boolean, string>
   */
  public function getDebugMode() {
    return $this->debug_mode;
  }

  /**
   * Enable/disable debug/sandbox mode
   */
  private function _setDebugMode($debug_mode = FALSE){
    if ($debug_mode === TRUE) {
      $this->debug_mode = TRUE;
    }

    if ($this->getDebugMode()) {
      $this->App->setSystemMessage('debug', 'Debug, logging and Sandbox mode is enabled.', 'warning');
      return TRUE;
    }
  }

}