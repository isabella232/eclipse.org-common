<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once(realpath(dirname(__FILE__) . "/../ads/eclipseAds.class.php"));

/**
 * Promoted Downloads (Packages)
 *
 * @author chrisguindon
 */
class PromotedDownloads extends EclipseAds {

  public function __construct($source = "") {
    parent::__construct($source);

    // Note: 1 slot = 20
    // Total = 100

    // EMPTY
    $Ad = new Ad();
    $Ad->setTitle('EMPTY');
    $Ad->setBody("EMPTY");
    $Ad->setImage("EMPTY");
    $Ad->setCampaign('EMPTY');
    $Ad->setUrl("https://");
    $Ad->setWeight(80);
    $Ad->setType('empty');
    $this->newAd($Ad);


    // YATTA
    $Ad = new Ad();
    $Ad->setTitle('Yatta Launcher for Eclipse');
    $Ad->setBody('Install, launch, and share your Eclipse IDE. Stop configuring. Start Coding.');
    $Ad->setImage('/downloads/images/launcherIcon42.png');
    $Ad->setCampaign('PROMO_DOWNLOAD_YATTA');
    $Ad->setUrl("https://www.eclipse.org/go/" . $Ad->getCampaign());
    $Ad->setWeight(20);
    $Ad->setType('default');
    $this->newAd($Ad);
  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "", $impression_id = "") {
    ob_start();
    // Layout A is default
    $tpl = "views/view.promotedDownloads.layout-a.tpl.php";
    // if Layout B is specified
    if ($layout == 'layout_b'){
      $tpl = "views/view.promotedDownloads.layout-b.tpl.php";
    }

    include($tpl);
    $this->output = ob_get_clean();
  }
}

