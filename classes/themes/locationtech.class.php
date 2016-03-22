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

class Locationtech extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('locationtech');
    parent::__construct($App);

    $this->setBaseUrl('https://www.locationtech.org');
    $base_url = $this->getBaseUrl();
    $theme_url = $this->getEclipseUrl() . $this->getThemeUrl('solstice');
    $logo = array();
    $logo['default_responsive'] = '<img src="' . $theme_url . 'public/images/locationtech/logo.png" alt="LocationTech logo" class="logo-eclipse-default img-responsive"/>';
    $logo['default'] = '<img src="' . $theme_url . 'public/images/locationtech/logo.png" alt="LocationTech logo" class="logo-eclipse-default"/>';
    $logo['mobile'] = '<img src="' . $theme_url . 'public/images/locationtech/logo.png" class="logo-eclipse-default-mobile" width="161" alt="LocationTech logo" />';
    $logo['white'] = '<img src="' . $theme_url . 'public/images/logo/eclipse-logo-bw-332x78.png" alt="Eclipse.org black and white logo" width="166" height="39" id="logo-eclipse-white"/>';
    $logo['default_thin'] = '<img src="' . $theme_url . 'public/images/locationtech/logo-color.png" alt="LocationTech logo" class="logo-eclipse-default img-responsive"/>';
    $logo['default_thin_link'] = '<a href="' . $base_url . '">' . $logo['default_thin'] . '</a>';
    $logo['default_link'] = '<a href="' . $base_url . '">' . $logo['default'] . '</a>';
    $logo['default_responsive_link'] = '<a href="' . $base_url . '">' . $logo['default_responsive'] . '</a>';
    $logo['mobile_link'] = '<a href="' . $base_url . '" class="navbar-brand visible-xs">' . $logo['mobile'] . '</a>';
    $this->setLogo($logo);
  }

  /**
   * Hook for making changes to $App when using setApp()
   *
   * @param App $App
   */
  public function _hookSetApp($App) {
    $App->setGoogleAnalyticsTrackingCode('UA-910670-10');
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
    $Breadcrumb->insertCrumbAt('2', 'Locationtech', 'https://www.locationtech.org', NULL);
    $this->Breadcrumb = $Breadcrumb;
  }

  /**
   * Get default variables for CFA
   *
   * @return array
   */
  protected function _getCfaButtonDefault() {
    $default['class'] = 'btn btn-huge btn-warning';
    $default['href'] = 'https://locationtech.org/mailman/listinfo/location-iwg';
    $default['text'] = '<i class="fa fa-users"></i> Getting Started';
    return $default;
  }
  /**
   * Get main-menu html output
   *
   * @return string
   */
  public function getMenu() {
    $Menu = $this->_getMenu();
    $main_menu = $Menu->getMenuArray();
    $variables = array();
    $DefaultMenu = new Menu();
    $default_menu_flag = FALSE;
    if ($DefaultMenu->getMenuArray() == $main_menu) {
      $App = $this->_getApp();
      ob_start();
      include($App->getBasePath() . '/themes/' . $this->getTheme() . '/_menu_links.php');
      return ob_end_flush();
    }

    // Main-menu
    foreach ($main_menu as $item) {
      $menu_li_classes = "";
      $caption = $item->getText();
      $items[] = '<li' . $menu_li_classes . '><a href="' . $item->getURL() . '" target="' . $item->getTarget() . '">' . $caption . '</a></li>';
    }

    return implode($items, '');
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
      return '<a href="https://www.locationtech.org/user/login/"><i class="fa fa-sign-in fa-fw"></i> Log in</a>';
    }
    return parent::getSessionVariables($id);
  }

}
