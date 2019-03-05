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

    $this->resetAttributes('header-left');
    $this->resetAttributes('main-menu-wrapper');
    $this->resetAttributes('header-right');
    $this->resetAttributes('main-sidebar');

    $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    $this->setAttributes('main-menu-wrapper', 'col-sm-19 col-md-20 margin-top-10');
    $this->setAttributes('main-menu-wrapper-no-header-right', 'col-sm-24 col-md-19 reset margin-top-10');

    $this->removeAttributes('main', 'no-promo');
    $this->setAttributes('breadcrumbs', 'breadcrumbs-default-margin');
    $this->setAlternateLayout();
    $this->removeAttributes('img_logo_default', 'img-responsive', 'class');
    $this->setAttributes('img_logo_default', '160', 'width');
    $this->setAttributes('img_logo_mobile', '160', 'width');

    // Featured footer
    $this->setAttributes('featured-footer', 'featured-footer featured-footer-newsletter');

    $featured_footer_bg_img = "https://www.eclipse.org/home/images/banner_image_survey.jpg";
    if(time() >= strtotime("4 March 2019") && time() < strtotime("24 March 2019")) {
      $featured_footer_bg_img = "https://eclipse.org/home/images/banner_jakarta_dev_survey_footer.jpg";
    }
    $this->setAttributes('featured-footer', "background-size:cover;background-image:url(". $featured_footer_bg_img .");background-repeat:no-repeat;background-position:center;clip-path:polygon(0 8%,100% 0,100% 100%,0 100%);-webkit-clip-path:polygon(0 8%,100% 0,100% 100%,0 100%);border-bottom:1px solid #ccc;",'style');

    // Set attributes on main sidebar
    $this->setAttributes('main-sidebar', 'main-sidebar-default-margin');

    $this->setAttributes('btn-call-for-action', 'float-right hidden-xs');
    $this->setAttributes('main-menu', 'float-sm-right');
    $this->removeAttributes('navbar-main-menu', 'reset');
  }

  public function setAlternateLayout($enable = FALSE) {
   $image_path = '//www.eclipse.org' . $this->getThemeUrl('solstice') . 'public/images/logo/';
   $default_logo = 'eclipse-foundation-white-orange.svg';
    if ($enable) {
      $default_logo = 'eclipse-foundation-grey-orange.svg';
      $this->setAttributes('body', 'alternate-layout');
    }
    else {
      $this->removeAttributes('body', 'alternate-layout');
    }

    // Set default images
    $this->setAttributes('img_logo_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_eclipse_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_eclipse_white', $image_path . 'eclipse-foundation-white.svg', 'src');
    $this->setAttributes('img_logo_mobile', $image_path . $default_logo, 'src');
  }

  /**
   * Implement BaseTheme::_getHeaderRight();
   *
   * Hide headerRight div if empty
   *
   * {@inheritDoc}
   * @see BaseTheme::getHeaderRight()
   */
  public function getHeaderRight(){
   return "";
  }

  /**
   * Implement BaseTheme::getHeaderLeft();
   *
   * Reset header left classes for thin layout
   *
   * {@inheritDoc}
   * @see BaseTheme::getHeaderLeft()
   */
  public function getHeaderLeft(){
    $layout_types = array(
      'thin',
      'thin-header',
      'thin-with-footer-min'
    );
    $cfa_button = $this->getCfaButton();
    if (in_array($this->getLayout(), $layout_types) && (!$this->getDisplayHeaderRight() || empty($cfa_button))) {
      $this->resetAttributes('header-left', 'class');
      $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    }
    return <<<EOHTML
      <div{$this->getAttributes('header-left')}>
        {$this->getLogo('default', TRUE)}
      </div>
EOHTML;
  }

  /**
   * Get Menu Prefix
   *
   * @return string
   */
  public function getMainMenuPrefix() {
    return $this->getCfaButton();
  }

  /**
   * Get Menu Suffix
   *
   * @return string
   */
  public function getMenuSuffix(){
    $suffix_items = array();

    $google_search = $this->getGoogleSearch();
    if (!empty($google_search)) {
      $suffix_items[] = <<<EOHTML
      <li class="dropdown eclipse-more main-menu-search">
        <a data-toggle="dropdown" class="dropdown-toggle" role="button"><i class="fa fa-search"></i> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li>
            <!-- Content container to add padding -->
            <div class="yamm-content">
              <div class="row">
                <div class="col-sm-24">
                  <p>Search</p>
                  {$this->getGoogleSearch()}
                </div>
              </div>
            </div>
          </li>
        </ul>
      </li>
EOHTML;
    }

   return implode('',$suffix_items);
  }

  /**
   * Implement BaseTheme::getFooterPrexfix()
   *
   * {@inheritDoc}
   * @see BaseTheme::getFooterPrexfix()
   */
  public function getFooterPrexfix() {
    $main_container_col = "col-sm-24";
    $promo_html = "";

    $promo_path = realpath(dirname(__FILE__) . "/../ads/promotions.class.php");
    if ($promo_path) {
      include_once ($promo_path);
      $foundation_promo = Promotions::output(array('foundation'));
      if (!empty($foundation_promo)) {
        $main_container_col = "col-sm-10 col-sm-offset-3 margin-bottom-20";
        $promo_html = '<div class="col-sm-8">' . $foundation_promo . '</div>';
      }
    }

    $content = '<h2 style="font-weight:400;">We Want to Hear from You!<br>
            2018 Eclipse Foundation Brand Survey</h2>
            <p style="font-size:18px;">Runs until December 14, 2018</p>
            <p><a class="btn btn-primary" href="https://bit.ly/2yS61ap">Take the Survey!</a></p>';

    if(time() >= strtotime("4 March 2019") && time() < strtotime("24 March 2019 10:00")) {
      $content = '<h2 style="color:#4c4d4e;"><strong>The Jakarta EE 2019 <br>Developer Survey is now available</strong></h2>
            <p><a class="btn btn-primary btn-lg" href="https://www.surveymonkey.com/r/fdnbanner">Take the Survey</a></p>';
    }

    if(time() >= strtotime("24 March 2019 10:00")) {
      $btn_url = $this->buildUrl("https://www.eclipse.org/go/PROMO_ECLIPSEIDE_FOOTER", array(
        'query' => array(
          'utm_source' => "eclipse_foundation",
          'utm_medium' => "featured_footer",
          'utm_campaign' => "eclipse_ide_2018_12",
        ),
        'absolute' => TRUE
      ));
      $content = '<p style="font-size:16px;" class="white">Now Available</p>
            <h2 style="font-size:52px;" class="white"><strong>Eclipse IDE 2018-12</strong></h2>
            <p class="white">Get the latest version of the Eclipse IDE!</p>
            <ul class="list-inline">
              <li><a class="btn btn-primary" href="'. $btn_url .'">Download</a></li>
              <li><a class="btn btn-simple" href="https://www.eclipse.org/eclipseide/">Learn More</a></li>
            </ul>';
    }

    return <<<EOHTML
    <!-- Sign Up to our Newsletter -->
    <div{$this->getAttributes('featured-footer')}>
      <div class="container">
        <div class="row">
          <div class="{$main_container_col}">
            {$content}
          </div>
          {$promo_html}
        </div>
      </div>
    </div>
EOHTML;
  }
}
