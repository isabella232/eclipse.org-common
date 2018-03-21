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

require_once ('solstice.class.php');
class Quicksilver extends solstice {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    parent::__construct($App);
    $this->setTheme('quicksilver');

    $this->setDisplayGoogleSearch(FALSE);

    $this->resetAttributes('header-left');
    $this->resetAttributes('main-menu-wrapper');
    $this->resetAttributes('header-right');

    $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    $this->setAttributes('main-menu-wrapper', 'col-sm-15 col-md-15 reset margin-top-10');
    $this->setAttributes('main-menu-wrapper-no-header-right', 'col-sm-24 col-md-19 reset margin-top-10');
    $this->setAttributes('header-right', 'col-sm-4 col-md-5 text-right hidden-print hidden-xs pull-right margin-top-10');

    $this->setAttributes('navbar-main-menu', 'float-right');

    $this->setAlternateLayout();
  }

  public function setAlternateLayout($enable = FALSE) {
    $image_path = $this->getThemeUrl('solstice') . 'public/images/logo/';
    $default_logo = 'eclipse-foundation-grey-orange.svg';
    if ($enable) {
      $default_logo = 'eclipse-foundation-white-orange.svg';
      $this->setAttributes('body', 'alternate-layout');
    }
    else {
      $this->removeAttributes('body', 'alternate-layout');
    }

    // Set default images
    $this->setAttributes('img_logo_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_default', 'logo-eclipse-default img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_eclipse_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_eclipse_default', 'img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_white', $image_path . 'eclipse-foundation-white.svg', 'src');
    $this->setAttributes('img_logo_eclipse_white', 'Eclipse.org black and white logo', 'alt');
    $this->setAttributes('img_logo_eclipse_white', 'logo-eclipse-white img-responsive');

    $this->setAttributes('img_logo_mobile', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_mobile', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_mobile', 'logo-eclipse-default-mobile img-responsive', 'class');

    // Default theme js file
    $this->setAttributes('script-theme-main-js', $this->getThemeUrl('solstice') . 'public/javascript/quicksilver.min.js', 'src');
  }

  /**
   * Implement BaseTheme::_getHeaderRight();
   *
   * Hide headerRight div if empty
   *
   * {@inheritDoc}
   * @see BaseTheme::_getHeaderRight()
   */
  protected function _getHeaderRight(){
    $google_search = $this->getGoogleSearch();
    $cfa_button = $this->getCfaButton();
    if (!$this->getDisplayHeaderRight() || (empty($google_search) && empty($cfa_button))) {
      $this->setDisplayHeaderRight(FALSE);
      $this->resetAttributes('main-menu-wrapper');
      $this->setAttributes('main-menu-wrapper', 'col-sm-19 col-md-20 reset margin-top-10');
      return "";
    }
  }

  /**
   * Implement BaseTheme::getFooterPrexfix()
   *
   * {@inheritDoc}
   * @see BaseTheme::getFooterPrexfix()
   */
  public function getFooterPrexfix() {
    return <<<EOHTML
    <!-- Sign Up to our Newsletter -->
    <div class="featured-footer featured-footer-newsletter">
      <div class="container">
        <p><i data-feather="mail" stroke-width="1"></i></p>
        <h2>Sign up to our Newsletter</h2>
        <p>A fresh new issue delivered monthly</p>
        <form action="https://www.eclipse.org/donate/process.php" method="post" target="_blank">
          <div class="form-group">
            <input type="hidden" name="type" value="newsletter">
            <input type="email" value="" name="email" class="textfield-underline form-control" id="mce-EMAIL" placeholder="Email">
          </div>
          <input type="submit" value="Subscribe" name="subscribe" class="button btn btn-warning">
        </form>
      </div>
    </div>
EOHTML;
  }
}
