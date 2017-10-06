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

    if (date("Y/m/d") >= "2017/09/05" && date("Y/m/d") < "2017/09/19") {
      $content['body'] ="Register now for EclipseCon Europe | October 24 - 26, 2017 | Ludwigsburg, Germany";
      $content['banner_styles'] = "background-color:#ce2227;";
    }

    if (date("Y/m/d") >= "2017/09/19" && date("Y/m/d") < "2017/10/06") {
      $content['body'] ="Register by October 5 to get the best price! EclipseCon Europe | October 24 - 26, 2017 | Ludwigsburg, Germany";
      $content['banner_styles'] = "background-color:#3a7939;";
    }

    if (date("Y/m/d") >= "2017/10/06" && date("Y/m/d") < "2017/10/17") {
      $content['body'] ="Two weeks left to register! EclipseCon Europe | October 24 - 26, 2017 | Ludwigsburg, Germany";
      $content['banner_styles'] = "background-color:#F68B1F;";
    }

    if (date("Y/m/d") >= "2017/10/17" && date("Y/m/d") < "2017/10/24") {
      $content['body'] ="One week to go! EclipseCon Europe | October 24 - 26, 2017 | Ludwigsburg, Germany";
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
    if ((date("Y/m/d") >= "2017/09/05" && date("Y/m/d") < "2017/10/24")) {
      $this->output = $this->ad->getHtml();
    }
  }
}