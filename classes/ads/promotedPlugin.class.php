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
  public function PromotedPlugin($source = "") {
    parent::__construct($source);

    $Ad = new Ad();
    $Ad->setTitle('Java 9 Support (Beta)');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=2393593');
    $Ad->setBody('Early access to Java 9 support for Mars.');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/default_images/default_2.png?itok=hA89-j9Y');
    $Ad->setCampaign('PP_JAVA9');
    $this->newAd($Ad);

    $Ad = new Ad();
    $Ad->setTitle('Buildship Gradle Integration');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=2306961');
    $Ad->setBody('Eclipse plug-ins that provide support for building software using Gradle.');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/Gradle-Logo.png?itok=I76toUo1');
    $Ad->setCampaign('PP_GRADLE');
    $this->newAd($Ad);
  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   */
  protected function _build() {
   ob_start();
   include("tpl/promotedPlugin.tpl.php");
   $this->output = ob_get_clean();
  }
}