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
/*
    $Ad = new Ad();
    $Ad->setBody('Your Eclipse is slow. Optimizer for Eclipse speeds up your IDE by
      finding and fixing common configuration issues in your Eclipse installation.');
    $Ad->setUrl('//marketplace.eclipse.org/marketplace-client-intro?mpc_install=2231050');
    $Ad->setTitle('Optimizer for Eclipse');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/OforE_marketplace-1.png');
    $Ad->setCampaign('PP_OPTIMIZER');
    $this->newAd($Ad);

    $Ad = new Ad();
    $Ad->setBody('Reactive Blocks lets you combine Java programming and visual
      development to build robust and concurrent IoT gateway applications.');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=1776078');
    $Ad->setTitle('Reactive Blocks');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/reactive-blocks-marketplace_0.png');
    $Ad->setCampaign('PP_REACTIVE');
    $this->newAd($Ad);
*/
    $Ad = new Ad();
    $Ad->setTitle('Gradle Integration');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=1799756');
    $Ad->setBody('The Eclipse-Integration-Gradle project brings you developer tooling for Gradle into Eclipse.');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/default_images/default_2.png');
    $Ad->setCampaign('PP_GRADLE');
    $this->newAd($Ad);

    $Ad = new Ad();
    $Ad->setTitle('MyEclipse Enterprise Workbench');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=69');
    $Ad->setBody('Where developers unite to craft masterful code');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/lowres_myeclipse_256x256.png');
    $Ad->setCampaign('PP_MYECLIPSE');
    $this->newAd($Ad);

    $Ad = new Ad();
    $Ad->setTitle('GlassFish Tools');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=2318313');
    $Ad->setBody('Tools for developing applications for GlassFish.');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/GlassFishToolsMarketplaceLogo_3.png');
    $Ad->setCampaign('PP_GLASSFISH');
    $this->newAd($Ad);

    $Ad = new Ad();
    $Ad->setTitle('JBoss Developer Studio');
    $Ad->setUrl('http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=1616973');
    $Ad->setBody('Includes support for JBoss and related technology; there are support for Hibernate, JBoss AS, CDI, Drools, jBPM, JSF, (X)HTML, Seam, Maven, JBoss Portal and more.');
    $Ad->setImage('//marketplace.eclipse.org/sites/default/files/styles/ds_medium/public/devstudio_logo_132x95_0.png');
    $Ad->setCampaign('PP_JBOSS');
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