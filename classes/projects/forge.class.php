<?php
/**
 * *****************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Wayne Beaton (Eclipse Foundation)- initial API and implementation
 * Christopher Guindon (Eclipse Foundation) - minor changes
 * *****************************************************************************
 */

/**
 * This class represents a forge instance (e.g.
 * Eclipse, LocationTech,
 * or PolarSys). The intent is to try and centralize the notion of a
 * forge rather than have bits of code here and there to handle all of
 * the different forges (i.e. to reduce the long term maintenance burden).
 */
class Forge {

  /**
   * Forge data
   *
   * @var array
   */
  public $data = array();

  /**
   * List of all forges
   *
   * @var array
   */
  private static $forges = array();

  /**
   * Constructor
   *
   * @param unknown $data
   */
  function __construct($data = array()) {
    $this->data = $data;
  }

  /**
   * Get $forges
   *
   * @return Forge[]
   */
  static function getForges() {
    if (!empty(self::$forges)) {
      return self::$forges;
    }

    $forges = array(
      'eclipse' => array(
        'id' => 'eclipse',
        'name' => 'Eclipse',
        'url' => 'https://projects.eclipse.org'
      ),
      'locationtech' => array(
        'id' => 'locationtech',
        'name' => 'LocationTech',
        'url' => 'https://www.locationtech.org'
      ),
      'polarsys' => array(
        'id' => 'polarsys',
        'name' => 'PolarSys',
        'url' => 'https://www.polarsys.org'
      )
    );

    foreach ($forges as &$forge) {
      $forge = new self($forge);
    }

    return self::$forges = $forges;
  }

  /**
   * Get specific forge
   *
   * @param string $id
   *
   * @return Forge
   */
  static function getForge($id) {
    $forges = self::getForges();
    if (isset($forges[$id])) {
      return $forges[$id];
    }
    return array();
  }

  /**
   * Get default forge
   *
   * @return Forge
   */
  static function getDefault() {
    return self::getForge('eclipse');
  }

  /**
   * Get forge from project id
   *
   * @param unknown $id
   *
   * @return NULL|Forge
   */
  static function getForgeForProjectId($id) {
    $segments = explode('.', $id);
    if ($segments[0] == 'foundation-internal') {
      return null;
    }

    foreach (self::getForges() as $id => $forge) {
      if ($id == $segments[0]) {
        return $forge;
      }
    }

    return self::getDefault();
  }

  /**
   * Get forge id
   *
   * @return string
   */
  function getId() {
    if (isset($this->data['id'])) {
      return $this->data['id'];
    }
    return "";
  }

  /**
   * Get forge name
   *
   * @return string
   */
  function getName() {
    if (isset($this->data['name'])) {
      return $this->data['name'];
    }
    return "";
  }

  /**
   * Get forge url
   *
   * @return string
   */
  function getUrl() {
    if (isset($this->data['url'])) {
      return $this->data['url'];
    }
    return "";
  }

  /**
   * Get local project id based off the forge
   *
   * @param unknown $id
   * @return unknown|NULL
   */
  function getLocalProjectId($id) {
    if ($this->isEclipseForge()) {
      return $id;
    }

    $forgeId = $this->getId();
    if (preg_match("/^$forgeId\.(.*)$/", $id, $matches)) {
      return $matches[1];
    }

    return null;
  }

  /**
   * Verify if current forge is Eclipse
   *
   * @return boolean
   */
  function isEclipseForge() {
    return $this->getId() == 'eclipse';
  }

}