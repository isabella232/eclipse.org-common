<?php
/**
 * Copyright (c) 2015, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once("ad.class.php");

/**
 * Eclipse Ads
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class EclipseAds {

 /**
  * The selected ad to display
  * @var Ad object
  */
  protected $ad = array();

  /**
   * List of all ads to chose from
   * @var array
   */
  protected $ads = array();

  /**
   * The HTML output of the ad
   * @var string
   */
  protected $output = "";

  /**
   * The description of the page that will display the ad
   * @var string
   */
  protected $source = "";

  /**
   * The total weight of all ads
   * @var int
   */
  protected $total_weight = 0;

  /**
   * Constructor
   * @param string $source
   */
  public function __construct($source = "") {
    if ($source != "") {
      $this->source =  $source;
    }
  }

  /**
   * The ad builder, this funciton is ussually overwritten in a parent class
   * @return string
   */
  protected function _build($layout = "", $type = "", $impression_id = "") {
    return "";
  }

  /**
   * Add an add to the list of ads
   * @param object $Ad
   */
  public function newAd($Ad = NULL) {
    if ($Ad->validAd()) {
      $this->ads[] = $Ad;
    }
  }

  /**
   * Randomly chose an ad from the list
   * @return boolean
   */
  protected function _choseAd() {
    if (!empty($this->ads)) {
      $this->_getTotalWeight();
      switch ($this->total_weight) {
        // split evenly
        case 0:
          $random = array_rand($this->ads, 1);
          $this->ad = $this->ads[$random];
          break;

        // use weight to chose an ad.
        default:
          $draw = rand(1, $this->total_weight);
          $current = 0;
          foreach ($this->ads as $Ad) {
            $current = $current + $Ad->getWeight();
            if ($draw <= $current) {
              $this->ad  = $Ad;
              break;
            }
          }
      }
    }
  }

  private function _getTotalWeight() {
    $this->total_weight = 0;
    foreach ($this->ads as $ad) {
      $this->total_weight = $ad->getWeight() + $this->total_weight;
    }
    return $this->total_weight;
  }

  /**
   * Return HTML of the add and count impression if possible
   */
  public function output($layout = "") {
    $this->_choseAd();

    if (!empty($this->ad)) {
      if ($this->ad->getType() == "empty") {
        return "";
      }
      $campaign = $this->ad->getCampaign();
      $impression_id = "";
      if (!empty($this->ad) && $campaign != "") {
        $CampaignImpression = new CampaignImpression($campaign);
        $impression_id = $CampaignImpression->recordImpression();
      }
      $this->_build($layout, $this->ad->getType(), $impression_id);
    }
    return $this->output;
  }
}