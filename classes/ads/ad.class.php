<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

require_once("campaignImpression.class.php");

/**
 * Advertisement
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class Ad {

  /**
   * The url of an ad
   * @var string
   */
  private $url = "";

 /**
   * The url of an ad
   *
   * Some ads might need two diffent urls.
   *
   * @var string
   */
  private $url2 = "";

  /**
   * The title for the ad
   * @var string
   */
  private $title = "";

  /**
   * The text content of the ad
   * @var unknown
   */
  private $body = "";

  /**
   * The path for the image in the ad
   * @var string
   */
  private $image = "";

  /**
   * The Eclipse campain key to track impressions
   * @var string
   */
  private $campaign = "";

  /**
   * Weight of an ad. If not set, the impressions will be split evenly.
   * @var int
   */
  private $weight = 0;


  /**
   * Setter for $url
   * @param string $url
   */
  public function setUrl($url = '') {
    $this->url = $url;
  }

  /**
   * Getter for $url
   * @param string $url
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Setter for $url2
   * @param string $url2
   */
  public function setUrl2($url = '') {
    $this->url2 = $url;
  }

  /**
   * Getter for $url2
   * @param string $url2
   */
  public function getUrl2() {
    return $this->url2;
  }

  /**
   * Setter for $title
   * @param string $title
   */
  public function setTitle($title = "") {
    $this->title = $title;
  }

  /**
   * Getter for $title
   * @param string $title
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Setter for $body
   * @param string $body
   */
  public function setBody($body = "") {
    $this->body = $body;
  }

  /**
   * Getter for $body
   * @param string $body
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * Setter for $image
   * @param string $image
   */
  public function setImage($image = "") {
    $this->image = $image;
  }

  /**
   * Getter for $image
   * @param string $image
   */
  public function getImage() {
    return $this->image;
  }

  /**
   * Setter for $campaign
   * @param string $campaign
   */
  public function setCampaign($campaign = "") {
    $this->campaign = $campaign;
  }

  /**
   * Getter for $campaign
   * @param string $campaign
   */
  public function getCampaign() {
    return $this->campaign;
  }

  /**
   * Setter for $weight
   * @param string $weight
   */
  public function setWeight($value = 0) {
    if (is_int($value)) {
      $this->weight = $value;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Getter for $weight
   * @param int $weight
   */
  public function getWeight($value = 0) {
    return $this->weight;
  }

  /**
   * Verify if this is a valid Ad
   * @return boolean
   */
  public function validAd() {
    if ($this->url == "" || $this->title  == "" || $this->body  == "" || $this->image  == "") {
      return FALSE;
    }
    return TRUE;
  }
}