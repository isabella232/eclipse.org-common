<?php
/**
 * Copyright (c) 2016, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once("eclipseAds.class.php");

class DownloadsBannerAd extends EclipseAds {

  public function __construct() {
    parent::__construct();
    if (time() < strtotime("23 October 2018")) {
      $campaign = "PROMO_ECE2018_DOWNLOADS_PAGE";
      $content['body'] = "Register now for EclipseCon Europe 2018 ~ Ludwigsburg, Germany ~ October 23 - 25, 2018";
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
  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "") {
    $this->output = $this->ad->getHtml();
  }
}