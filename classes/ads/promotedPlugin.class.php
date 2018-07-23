<?php
/**
 * Copyright (c) 2015, 2018 Eclipse Foundation.
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

require_once("eclipseAds.class.php");

/**
 * PromotedPlugin
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class PromotedPlugin extends EclipseAds {

  /**
   * Constructor
   *
   * @param string $source
   *   The description of the location of the ad.
   */
  public function __construct($source = "") {
    parent::__construct($source);

    /* Note: Keeping the next block as a reference.
             If we decide to add the promoted plugins back to display */

    /*$Ad = new Ad();
    $Ad->setTitle('Java 9 Support (Beta)');
    $this->_setMarketplaceNodeId('2393593', $Ad);
    $Ad->setBody('Early access to Java 9 support for Mars.');
    $Ad->setImage('/downloads/images/promoted_listings/default.png');
    $Ad->setCampaign('PP_JAVA9');
    $Ad->setWeight(50);
    $this->newAd($Ad);*/

  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   */
  protected function _build($layout = "", $type = "") {
    ob_start();
    include("tpl/promotedPlugin.tpl.php");
    $this->output = ob_get_clean();
  }

  /**
   * Set Links for a promoted plugin
   * @param string $id
   * @param unknown $Ad
   */
  protected function _setMarketplaceNodeId($id = '', &$Ad) {
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=' . $id);
    $Ad->setUrl2('http://marketplace.eclipse.org/node/' . $id);
  }

}