<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *    Eric Poirier (Eclipse Foundation)
 *******************************************************************************/

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

require_once(dirname(__FILE__) . "/../../system/eclipseenv.class.php");

class EclipseInstaller extends EclipseEnv {

  private $platform = array();

  private $total_download_count = 0;

  private $json_data = array();

  private $layout = "layout_b";

  private $download_link = array();

  /**
   * Constructor
   */
  function EclipseInstaller($release = NULL) {
    parent::__construct();
    $this->_addPlaform('Mac OS X');
    $this->_addPlaform('Windows');
    $this->_addPlaform('Linux');

    // Let's load the json feed to get the links
    // for this release.
    if (!is_null($release)) {
      $this->_loadJson($release);
    }
  }

  /**
   * Add a link to the Eclipse Installer
   *
   * @param string $platform
   * @param string $url
   * @param string $text
   * @return boolean
   */
  public function addlink($platform = '', $url = '', $text = '') {

    if(!isset($this->platform[$this->_removeSpaces($platform)])) {
      return FALSE;
    }
    $link_classes = "";
    $count = count($this->platform[$this->_removeSpaces($platform)]['links']);
    $platform_array = array(
      'platform' => $platform,
      'count' =>$count,
      'link_classes' => "btn btn-warning",
      'url' => $url,
      'text' => $text,
      'text_prefix' => 'Download',
    );

    $this->setPlatform($platform_array);
  }

  /**
   * Output of the Eclipse Installer HTML
   *
   * @return string
   */
  public function output() {
    // Find out what OS the user is on
    require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
    $App = new App();
    $os_client = $App->getClientOS();
    $display = "windows"; // setting windows as default display
    if ($os_client == "linux" || $os_client == "linux-x64") {
      $display = "linux";
    }
    if ($os_client == "macosx" || $os_client == "cocoa64" || $os_client == "carbon") {
      $display = "macosx";
    }

    // Check if the OS has been selected manually
    if (isset($_GET['osType'])) {
      $display = $_GET['osType'];
      if ($_GET['osType'] == 'win32') {
        $display = "windows";
      }
    }

    $platforms = $this->getPlatform();
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $layout = $this->getInstallerLayout();

    foreach ($platforms as $platform_key => &$platform) {
      foreach ($platform['links'] as $link_key => $link) {

        // Remove every 32 bit links for the Layout A
        if ($link['text'] == '32 bit' && $layout == 'layout_a') {
          unset($platforms[$platform_key]['links'][$link_key]);
        }
      }
      if ($display == strtolower(str_replace(' ', '', $platform['label']))) {
        $this->setDownloadLink($platform);
      }
    }
    $download_count = $this->total_download_count;
    if (!empty($platforms)) {
      switch ($layout) {
        case 'layout_a':
          $tpl = "view/eclipseInstallerLayoutA.php";
          break;
        case 'layout_b':
          $tpl = "view/eclipseInstallerLayoutB.php";
          break;
      }
      ob_start();
      include($tpl);
      $html = ob_get_clean();
    }
    return $html;
  }

  /**
   * Returns the layout for the Installer
   *
   * @return string
   */
  public function getInstallerLayout() {
    return $this->layout;
  }

  /**
   * Sets a specified layout for the Installer
   *
   * @param string $layout
   */
  public function setInstallerLayout($layout = "") {
    if (filter_var($layout, FILTER_SANITIZE_STRING)) {
      $this->layout = $layout;
    }
  }

  /**
   * Returns the download link
   *
   * @return array
   */
  public function getDownloadLink() {
    return $this->download_link;
  }

  /**
   * Set the download link
   *
   * @param array $link
   */
  public function setDownloadLink($link = array()) {
    $this->download_link = $link;
  }

  /**
   * Return a platform
   *
   * @return array
   */
  public function getPlatform() {
    return $this->platform;
  }

  /**
   * Sets a specified platform
   *
   * @param array $platform
   */
  public function setPlatform($platform = array()) {
    $this->platform[$this->_removeSpaces($platform['platform'])]['links'][] = $platform;
  }

  /**
   * Add links from json data feed.
   */
  private function _addLinksFromJson() {
    $data = $this->json_data;
    $eclipse_env = $this->getEclipseEnv();

    if (!empty($data['files']['mac64'])) {
      $this->addlink('Mac OS X', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['mac64']['url']), "64 bit");
    }

    if (!empty($data['files']['win32'])) {
      $this->addlink('Windows', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['win32']['url']), '32 bit');
    }

    if (!empty($data['files']['win64'])) {
      $this->addlink('Windows', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['win64']['url']), '64 bit');
    }

    if (!empty($data['files']['linux32'])) {
      $this->addlink('Linux', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['linux32']['url']), '32 bit');
    }

    if (!empty($data['files']['linux64'])) {
      $this->addlink('Linux', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['linux64']['url']), "64 bit");
    }
  }

  /**
   * Add a platform to the Eclipse Installer
   *
   * @param string $label
   */
  private function _addPlaform($label = '') {
   $safe_label = $this->_removeSpaces($label);
    $this->platform[$safe_label] = array(
      'label' => $label,
      //'icon' => '<img src="/downloads/assets/public/images/icon-' . $safe_label . '.png"/>',
      'icon' => '',
      'links' => array(),
    );
  }

  /**
   * Remove all spaces from a string.
   *
   * @param string $str
   */
  private function _removeSpaces($str = '') {
   return str_replace(' ', '', strtolower($str));
  }

  /**
   * Load jSON data from file.
   * @param unknown $release
   */
  private function _loadJson($release) {
    $url = '/home/data/httpd/writable/community/eclipse_installer.json';
    $json_data =  json_decode(file_get_contents($url), TRUE);
    foreach ($json_data as $data) {
      if (strtolower($data['release_title']) == strtolower($release)) {
        $this->json_data = $data;
        $this->_addLinksFromJson();
        if (!empty($this->json_data['total_download_count'])) {
          $this->total_download_count = $this->json_data['total_download_count'];
        }
        break;
      }
    }
  }
}