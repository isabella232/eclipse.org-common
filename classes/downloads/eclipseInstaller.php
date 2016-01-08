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
 *******************************************************************************/

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

class EclipseInstaller {

  private $platform = array();

  private $total_download_count = 0;

  private $json_data = array();

  /**
   * Constructor
   */
  function EclipseInstaller($release = NULL) {
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
   $count = count($this->platform[$this->_removeSpaces($platform)]['links']);
   $this->platform[$this->_removeSpaces($platform)]['links'][] = '<li class="download-link-' . $count . '"><a href="' . $url .'" title="' . $text . ' Download">' . $text .'</a></li>' . PHP_EOL;
  }

  /**
   * Output of the Eclipse Installer HTML
   *
   * @return string
   */
  public function output() {
    $html = "";
    $platforms = $this->platform;
    $download_count = $this->total_download_count;
    if (!empty($platforms)) {
      ob_start();
      include("view/eclipseInstaller.php");
      $html = ob_get_clean();
    }
    return $html;
  }

  /**
   * Add links from json data feed.
   */
  private function _addLinksFromJson() {
    $data = $this->json_data;

    if (!empty($data['files']['mac64'])) {
      $this->addlink('Mac OS X', $data['files']['mac64']['url'], '64 bit');
    }

    if (!empty($data['files']['win32'])) {
      $this->addlink('Windows', $data['files']['win32']['url'], '32 bit');
    }

    if (!empty($data['files']['win64'])) {
      $this->addlink('Windows', $data['files']['win64']['url'], '64 bit');
    }

    if (!empty($data['files']['linux32'])) {
      $this->addlink('Linux', $data['files']['linux32']['url'], '32 bit');
    }

    if (!empty($data['files']['linux64'])) {
      $this->addlink('Linux', $data['files']['linux64']['url'], '64 bit');
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