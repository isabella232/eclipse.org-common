<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

/**
 * Base class
 *
 * @author chrisguindon
 */
class EclipseEnv {

  /**
   * The Eclipse App() class.
   * This is useful for making calls to our databases.
   */
  protected $App = NULL;

  /**
   * Debug mode state
   *
   * @var bool
   */
  private $debug_mode = FALSE;

  /**
   * Cookie domain value
   *
   * @var string
   */
  private $prefix_cookie = '';

  /**
   * Eclipse dev domain prefix
   *
   * This could be dev.eclipse.org, dev.eclipse.local
   *
   * @var string
   */
  private $prefix_devdomain = '';

  /**
   * Eclipse domain prefix
   *
   * This could be www.eclipse.org, staging.eclipse.org or
   * www.eclipse.local
   *
   * @var string
   */
  private $prefix_domain = '';

  /**
   * Constructor
   */
  public function EclipseEnv(App $App = NULL) {
    if (is_null($App)) {
      require_once("app.class.php");
      $App = new App();
    }
    $this->App = $App;
    $this->_set_prefix();
    if ($this->_get_prefix_domain() != 'www.eclipse.org') {
      $this->_set_debug_mode(TRUE);
    }
  }

  /**
   * Get eclipse.org cookie domain and prefix based off the current environment
   *
   * @return array
   */
  public function getEclipseEnv(){

    // @todo: allowed_hosts is deprecated
    $server['dev'] = array(
      'shortname' => 'local',
      'cookie' => '.eclipse.local',
      'domain' => 'www.eclipse.local',
      'dev_domain' => 'dev.eclipse.local',
      'allowed_hosts' => array(
        'eclipse.local',
        'www.eclipse.local',
        'dev.eclipse.local',
        'docker.local'
      ),
    );

    $server['staging'] = array(
      'shortname' => 'staging',
      'cookie' => '.eclipse.org',
      'domain' => 'staging.eclipse.org',
      // We currently dont have a staging server for dev.eclipse.org
      'dev_domain' => 'dev.eclipse.org',
      'allowed_hosts' => array(
        'staging.eclipse.org'
      ),
    );

    $server['prod'] = array(
      'shortname' => 'prod',
      'cookie' => '.eclipse.org',
      'domain' => 'www.eclipse.org',
      'dev_domain' => 'dev.eclipse.org',
      'allowed_hosts' => array(
        // Empty, since it's the default.
      ),
    );

    if (strpos($_SERVER['SERVER_NAME'], '.local')){
      return $server['dev'];
    }

    if (strpos($_SERVER['SERVER_NAME'], 'staging')) {
      return $server['staging'];
    }

    return $server['prod'];
  }

  /**
   * Get shortname
   */
  public function getEnvShortName(){
    $domain = $this->getEclipseEnv();
    return $domain['shortname'];
  }

  /**
   * Get debug mode value
   *
   * @return Ambigous <boolean, string>
   */
  protected function _get_debug_mode() {
    return $this->debug_mode;
  }

  /**
   * Get domain prefix
   */
  protected function _get_prefix_domain() {
    return $this->prefix_domain;
  }

  /**
   * Get devdomain prefix
   */
  protected function _get_prefix_devdomain() {
    return $this->prefix_devdomain;
  }

  /**
   * Get cookie prefix
   */
  protected function _get_prefix_cookie() {
    return $this->prefix_cookie;
  }

  /**
   * Enable/disable debug/sandbox mode
   */
  private function _set_debug_mode($debug_mode = FALSE){
    $this->debug_mode = $debug_mode;
  }

  /**
   * Set Eclipse domain and Eclipse cookie domain
   */
  private function _set_prefix() {
    $domain = $this->getEclipseEnv();
    $this->prefix_domain = $domain['domain'];
    $this->prefix_devdomain = $domain['dev_domain'];
    $this->prefix_cookie = $domain['cookie'];
  }
}
