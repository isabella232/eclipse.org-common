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

require_once("ad.class.php");

/**
 * Eclipse Ads
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class EclipseAds{

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
   * The remote address of the user that will view the ad
   * @var string
   */
  protected $remote_addr = "";

  /**
   * Constructor
   * @param string $source
   */
  public function EclipseAds($source = "") {
    if ($source != "") {
      $this->source =  $source;
    }
    $this->remote_addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
  }

  /**
   * The ad builder, this funciton is ussually overwritten in a parent class
   * @return string
   */
  protected function _build() {
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
      $random = array_rand($this->ads, 1);
      $this->ad = $this->ads[$random];
    }
    return FALSE;
  }

  /**
   * Return HTML of the add and count impression if possible
   */
  public function output() {
    $this->_choseAd();
    $campaign = $this->ad->getCampaign();
    if (!empty($this->ad) && $campaign != "") {
      $CampaignImpression = new CampaignImpression($campaign, $this->source, $this->remote_addr);
      $CampaignImpression->recordImpression();
    }
    $this->_build();
    print $this->output;
  }
}