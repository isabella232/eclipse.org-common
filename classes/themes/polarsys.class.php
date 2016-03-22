<?php
/**
 * *****************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

require_once ('baseTheme.class.php');
class Polarsys extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('polarsys');
    parent::__construct($App);

    $this->setBaseUrl('https://www.polarsys.org');
    $base_url = $this->getBaseUrl();
    $theme_url = 'https://www.eclipse.org' . $this->getThemeUrl('solstice');
    $logo = array();
    $logo['default_responsive'] = '<img src="' . $theme_url . 'public/images/polarsys/logo.png" alt="PolarSys logo" class="logo-eclipse-default img-responsive"/>';
    $logo['default'] = '<img src="' . $theme_url . 'public/images/polarsys/logo.png" alt="PolarSys logo" class="logo-eclipse-default"/>';
    $logo['mobile'] = '<img src="' . $theme_url . 'public/images/polarsys/logo.png" class="logo-eclipse-default-mobile" width="161" alt="PolarSys logo" />';
    $logo['white'] = '<img src="' . $theme_url . 'public/images/logo/eclipse-logo-bw-332x78.png" alt="Eclipse.org black and white logo" width="166" height="39" id="logo-eclipse-white"/>';
    $logo['default_link'] = '<a href="' . $base_url . '">' . $logo['default'] . '</a>';
    $logo['default_responsive_link'] = '<a href="' . $base_url . '">' . $logo['default_responsive'] . '</a>';
    $logo['mobile_link'] = '<a href="' . $base_url . '" class="navbar-brand visible-xs">' . $logo['mobile'] . '</a>';
    $logo['eclipse_footer'] = '<img class="logo-eclipse-default img-responsive" src="' . $theme_url . 'public/images/logo/eclipse-800x188.png" alt="Eclipse Foundation homepage" />';
    $logo['eclipse_footer_link'] = '<a href="https://www.eclipse.org/" title="Eclipse Foundation">' . $logo['eclipse_footer'] . '</a>';
    $logo['polarsys_sectors'] = '<img class="img-responsive" typeof="foaf:Image" src="' . $theme_url . 'public/images/polarsys/header-bg-icons.png" alt="PolarSys sectors" />';

    $this->setLogo($logo);
  }

  /**
   * Hook for making changes to $App when using setApp()
   *
   * @param App $App
   */
  public function _hookSetApp($App) {
    $App->setGoogleAnalyticsTrackingCode('UA-910670-9');
  }

  /**
   * Set $Breadcrumb
   *
   * @param Breadcrumb $Breadcrumb
   */
  public function setBreadcrumb($Breadcrumb = NULL) {
    if (!$Breadcrumb instanceof Breadcrumb) {
      $App = $this->_getApp();
      require_once ($App->getBasePath() . '/system/breadcrumbs.class.php');
      $Breadcrumb = new Breadcrumb();
    }
    $Breadcrumb->insertCrumbAt('1', 'Eclipse Working Groups', 'https://www.eclipse.org/org/workinggroups', NULL);
    $Breadcrumb->insertCrumbAt('2', 'PolarSys', 'https://www.polarsys.org', NULL);
    $this->Breadcrumb = $Breadcrumb;
  }

  /**
   * Get default variables for CFA
   *
   * @return array
   */
  protected function _getCfaButtonDefault() {
    $default['class'] = 'btn btn-huge btn-warning';
    $default['href'] = 'https://www.polarsys.org/polarsys-downloads';

    $default['text'] = '<i class="fa fa-download"></i> Download';
    return $default;
  }

  /**
   * Get Default solstice Menu()
   *
   * @return Menu
   */
  protected function _getMenuDefault() {
    $base_url = $this->getBaseUrl();
    $App = $this->_getApp();
    require_once ($App->getBasePath() . '/system/menu.class.php');
    $Menu = new Menu();
    $Menu->setMenuItemList(array());
    $Menu->addMenuItem("About", $base_url . "/about-us", "_self");
    $Menu->addMenuItem("Solutions", $base_url . "/solutions", "_self");
    $Menu->addMenuItem("Community", $base_url . "/community", "_self");
    $Menu->addMenuItem("Contact Us", $base_url . "/contact-us", "_self");
    $Menu->addMenuItem("Members", $base_url . "/members-list", "_self");
    return $Menu;
  }

  /**
   * Get $ession_variables
   *
   * @param string $id
   *
   * @return string
   */
  public function getSessionVariables($id = "") {
    $Session = $this->_getSession();
    if ($id == "my_account_link" && !$Session->isLoggedIn()) {
      return '<a href="https://www.polarsys.org/user/login/"><i class="fa fa-sign-in fa-fw"></i> Log in</a>';
    }
    return parent::getSessionVariables($id);
  }
}
