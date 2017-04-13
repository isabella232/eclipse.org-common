<?php
/*******************************************************************************
 * Copyright(c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier(Eclipse Foundation)
 *******************************************************************************/
require_once("eclipseAds.class.php");

class DownloadsBannerAd extends EclipseAds {

  public function __construct() {
    parent::__construct($source);

    $campaign = "PROMO_FRANCE2017_DOWNLOADS_PAGE";

    $content['body'] ="";
    $content['banner_styles'] = "";

    if (date("Y/m/d") >= "2017/04/21" && date("Y/m/d") < "2017/05/06") {
      $content['body'] ="Early registration prices end May 5! EclipseCon France 2017, June 21 - 22, Toulouse";
      $content['banner_styles'] = "background-color:#ce2227;";
    }

    if (date("Y/m/d") >= "2017/05/10" && date("Y/m/d") < "2017/05/24") {
      $content['body'] ="Register now for EclipseCon France 2017, June 21-22, Toulouse";
      $content['banner_styles'] = "background-color:#3a7939;";
    }

    if (date("Y/m/d") >= "2017/06/07" && date("Y/m/d") < "2017/06/14") {
      $content['body'] ="EclipseCon France: Two weeks left to register! June 21-22, Toulouse";
      $content['banner_styles'] = "background-color:#F68B1F;";
    }

    if (date("Y/m/d") >= "2017/06/14" && date("Y/m/d") < "2017/06/21") {
      $content['body'] ="Last week left to register for EclipseCon France! June 21-22, Toulouse";
      $content['banner_styles'] = "background-color:#F68B1F;";
    }

    $content['button_text'] = "Register Today!";
    $content['button_url'] = $campaign;

    // Create the ad
    $Ad = new Ad();
    $Ad->setTitle('Downloads banner ad');
    $Ad->setCampaign($campaign);
    $Ad->setFormat("html");
    $Ad->setHtml('tpl/downloadsBannerAd.tpl.php', $content);
    $Ad->setType('paid');
    $Ad->setWeight('100');
    $this->newAd($Ad);

  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "") {

    // Check if the ad should be printed depending on the date
    if ((date("Y/m/d") >= "2017/04/21" && date("Y/m/d") < "2017/05/06") ||
        (date("Y/m/d") >= "2017/05/10" && date("Y/m/d") < "2017/05/24") ||
        (date("Y/m/d") >= "2017/06/07" && date("Y/m/d") < "2017/06/14") ||
        (date("Y/m/d") >= "2017/06/14" && date("Y/m/d") < "2017/06/21")) {
      $this->output = $this->ad->getHtml();
    }
  }
}