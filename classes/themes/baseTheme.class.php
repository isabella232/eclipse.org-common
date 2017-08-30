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

class BaseTheme {

  /**
   *
   * @var App()
   */
  protected $App = NULL;

  /**
   * Store
   *
   * @var array
   */
  protected $attributes = array();

  /**
   * Theme base url
   *
   * For example ://www.polarsys.org
   *
   * @var string
   */
  protected $base_url = "";

  /**
   * Base URL for login links
   *
   * @var string
   */
  protected $base_url_login = "";

  /**
   *
   * @var Breadcrumb()
   */
  protected $Breadcrumb = NULL;

  /**
   * Display header right html
   *
   * @var bool
   */
  protected $display_header_right = TRUE;

  /**
   * More menu flag
   *
   * @var bool
   */
  protected $display_more = TRUE;

  /**
   * Inlude user toolbar in output
   *
   * @var unknown
   */
  protected $display_toolbar = TRUE;


  /**
   * Display google search in output
   *
   * @var unknown
   */
  protected $display_google_search = TRUE;

  /**
   * Extra headers for <head>
   *
   * @var unknown
   */
  protected $extra_headers = "";

  /**
   * Google analytics code
   *
   * @var string
   */
  protected $ga_code = "";

  /**
   * Page HTML content
   *
   * @var string
   */
  protected $html = "";

  /**
   * Page layout
   *
   * @var string
   */
  protected $layout = "";

  /**
   * List of theme logos
   *
   * @var array
   */
  protected $logos = array();

  /**
   *
   * @var Menu()
   */
  protected $Menu = NULL;

  /**
   * Metatags
   *
   * @var array
   */
  protected $metatags = array();

  /**
   *
   * @var Nav()
   */
  protected $Nav = NULL;

  /**
   * Page Author
   *
   * @var string
   */
  protected $page_author = "";

  /**
   * Page Keywords
   *
   * @var string
   */
  protected $page_keywords = "";

  /**
   * Page Title
   *
   * @var string
   */
  protected $page_title = "";

  /**
   * Eclipse Promo HTML
   *
   * @var unknown
   */
  protected $promo_html = NULL;

  /**
   *
   * @var Session()
   */
  protected $Session = NULL;

  /**
   * User Session information
   *
   * List of links based of the session status.
   *
   * @var array
   */
  protected $session_variables = array();

  /**
   * The current theme name
   *
   * @var string
   */
  protected $theme = "baseTheme";

  /**
   * List of theme_variables
   *
   * @var unknown
   */
  protected $theme_variables = array();

  /**
   * Constructor
   */
  function __construct($App = NULL) {
    $this->setApp($App);
    $image_path = '//www.eclipse.org' . $this->getThemeUrl('solstice') . 'public/images/logo/';

    // Set default images
    $this->setAttributes('img_logo_default', $image_path . 'eclipse-426x100.png', 'src');
    $this->setAttributes('img_logo_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_default', 'logo-eclipse-default img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_default', $image_path . 'eclipse-426x100.png', 'src');
    $this->setAttributes('img_logo_eclipse_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_eclipse_default', 'img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_white', $image_path . 'eclipse-logo-bw-332x78.png', 'src');
    $this->setAttributes('img_logo_eclipse_white', 'Eclipse.org black and white logo', 'alt');
    $this->setAttributes('img_logo_eclipse_white', 'logo-eclipse-white img-responsive');

    $this->setAttributes('img_logo_mobile', $image_path . 'eclipse-800x188.png', 'src');
    $this->setAttributes('img_logo_mobile', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_mobile', 'logo-eclipse-default-mobile img-responsive', 'class');

    // Set attributes on mobile logo
    $this->setAttributes('link_logo_mobile', 'navbar-brand visible-xs', 'class');

    // Set attributes on body
    $this->setAttributes('body', 'body_solstice', 'id');

    // Set attribute on toolbar
    $this->setAttributes('toolbar-container-wrapper', 'clearfix toolbar-container-wrapper');
    $this->setAttributes('toolbar-container', 'container');
    $this->setAttributes('toolbar-row', 'text-right toolbar-row row hidden-print');
    $this->setAttributes('toolbar-user-links', 'col-md-24 row-toolbar-col');

    // Set attributes for header
    $this->setAttributes('header-wrapper', 'header-wrapper', 'id');
    $this->setAttributes('header-container', 'container');
    $this->setAttributes('header-row', 'header-row', 'id');
    $this->setAttributes('header-row', 'row');
    $this->setAttributes('header-left', 'header-left', 'id');
    $this->setAttributes('header-left', 'hidden-xs');
    $this->setAttributes('header-right', 'header-right', 'id');

    // Set attributes on CFA button
    $this->setAttributes('btn-call-for-action', 'btn-call-for-action', 'id');

    // Set attributes on main-menu
    $this->setAttributes('main-menu-wrapper', 'main-menu-wrapper', 'id');
    $this->setAttributes('main-menu', 'main-menu', 'id');
    $this->setAttributes('main-menu', 'navbar yamm');
    $this->setAttributes('main-menu-ul-navbar', 'nav navbar-nav');

    // Set attributes on breadcrumbs
    $this->setAttributes('breadcrumbs', 'breadcrumb', 'id');
    $this->setAttributes('breadcrumbs', 'hidden-print');

    // Set attributes on main content
    $this->setAttributes('main', 'main', 'role');
    $this->setAttributes('main', 'no-promo');
    $this->setAttributes('main-container', 'novaContent');
    $this->setAttributes('main-container', 'novaContent', 'id');

    // Set attributes on footer
    $this->setAttributes('footer1', 'footer-eclipse-foundation', 'id');
    $this->setAttributes('footer2', 'footer-legal', 'id');
    $this->setAttributes('footer3', 'footer-useful-links', 'id');
    $this->setAttributes('footer4', 'footer-other', 'id');

  }

  /**
   * Get $App
   *
   * @return App()
   */
  protected function _getApp() {
    if (!$this->App instanceof App) {
      $this->setApp();
    }
    return $this->App;
  }

  /**
   * Set $App
   *
   * @param App() $App
   */
  public function setApp($App = NULL) {
    if (!$App instanceof App) {
      if (!class_exists('App')) {
        require_once(realpath(dirname(__FILE__) . '/../../system/app.class.php'));
      }
      $App = new App();
    }
    $this->_hookSetApp($App);
    $this->App = $App;
  }

  /**
   * Hook for making changes to $App when using setApp()
   *
   * @param App $App
   */
  protected function _hookSetApp(App $App) {

  }

  /**
   * Get the HTML of the Share buttons
   *
   * @return string
   */
  public function getShareButtonsHTML() {
    $display_sharethis = $this->getThemeVariables('sharethis');
    if ($display_sharethis) {
      return '<div class="sharethis-inline-share-buttons"></div>';
    }
    return "";
  }

  /**
   * Get the JS of the Share buttons
   * (This should go in the head of the html page)
   *
   * @return string
   */
  public function getShareButtonsJS() {
    $display_sharethis = $this->getThemeVariables('sharethis');
    if ($display_sharethis) {
      return '<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58af133aa168200011ff98fe&product=inline-share-buttons"></script>';
    }
    return "";
  }

  /**
   * Get Attributes
   *
   * Fetch a specific attribute.
   *
   * @param string $element
   *        - Attribute name, i.e, body.
   * @param string $type
   *        - Either class or id.
   *
   * @return string
   */
  public function getAttributes($element = '', $type = NULL) {

    $allowed_type = array(
      'class',
      'id',
      'alt',
      'title',
      'href',
      'height',
      'width',
      'src',
      'title',
    );

    // If type is null, we shall return the string with both class and id.
    if (is_null($type)) {
      $html = array();
      if (is_string($element) && !empty($element)) {
        foreach ($allowed_type as $type) {
          if (isset($this->attributes[$type][$element]) && is_array($this->attributes[$type][$element])) {
            $html[] = $type . '="' . implode(' ', $this->attributes[$type][$element]) . '"';
          }
        }
      }

      // Add a space if we have someting to return.
      $prefix = "";
      if (!empty($html)) {
        $prefix = " ";
      }

      return $prefix . implode(" ", $html);
    }

    // If type is set, return only class or id values.
    if (in_array($type, $allowed_type) && is_string($element) && !empty($element)) {
      if (isset($this->attributes[$type][$element]) && is_array($this->attributes[$type][$element])) {
        return implode(' ', $this->attributes[$type][$element]);
      }
    }

    return '';
  }

  public function resetAttributes($element = '', $type = 'class') {
    $this->attributes[$type][$element] = array();
  }

  /**
   * Set Attributes
   *
   * Set a specific attributes
   *
   * @param string $element
   *        - Attribute name, i.e, body.
   * @param string $value
   *        - Attribute value
   * @param string $type
   *        - Either class or id.
   *
   * @return array $attributes
   */
  public function setAttributes($element = '', $value = "", $type = 'class') {
    $allowed_type = array(
      'class',
      'id',
      'alt',
      'title',
      'href',
      'height',
      'width',
      'src',
      'title',
    );

    $type = strtolower($type);
    $value = explode(' ', $value);
    foreach ($value as $val) {
      if (in_array($type, $allowed_type) && is_string($element) && !empty($element) && !empty($val)) {
        switch ($type) {
          case 'class':
            // Append classes instead of overriting them.
            // This way we can set multiple classes for differents contexts.
            if (!isset($this->attributes[$type][$element]) || !in_array($val, $this->attributes[$type][$element])) {
              $this->attributes['class'][$element][] = $val;
            }
            break;

          // For everything else, we only keep the last value set.
          default:
            $this->attributes[$type][$element] = array(
              $val
            );
            break;
        }
      }
    }
    return $this->attributes;
  }

  function getBareboneAssets() {
    $url = $this->getEclipseUrl() . $this->getThemeUrl("solstice") . "public/stylesheets/";
    $current_theme = $this->getTheme();
    if ($current_theme !== "solstice") {
      $url .= $current_theme . '-';
    }
    $url .= 'barebone.min.css';
    return <<<EOHTML
    <style type="text/css">
    @import url('{$url}')
    </style>
    <script
      src="{$this->getEclipseUrl()}/eclipse.org-common/themes/solstice/public/javascript/barebone.min.js">
    </script>
EOHTML;
  }

  /**
   * Set $base_url
   *
   * The $base_url is a prefix for hardcoded links
   * to the main site. For example, the footer and
   * the main menu.
   *
   * @param string $url
   */
  public function setBaseUrl($url = "") {
    $this->base_url = $url;
  }

  /**
   * Get $base_url
   *
   * The $base_url is a prefix for hardcoded links
   * to the main site. For example, the footer and
   * the main menu.
   *
   * @return string
   */
  public function getBaseUrl() {
    if (empty($this->base_url)) {
      $App = $this->_getApp();
      $this->base_url = $App->getWWWPrefix() . '/';
    }
    return $this->base_url;
  }

  /**
   * Get $base_url_login
   *
   * @return string
   */
  public function getBaseUrlLogin() {
    if (empty($this->base_url_login)) {
      $domain = $this->App->getEclipseDomain();
      $this->base_url_login = 'https://' . $domain['accounts'];
    }
    return $this->base_url_login;
  }

  /**
   * Set $base_url_login
   *
   * @param string $url
   */
  public function setBaseUrlLogin($url = "") {
    $this->base_url_login = $url;
  }

  /**
   * Get Breadcrumb HTML output
   *
   * @return string
   */
  public function getBreadcrumbHtml() {
    // Breadcrumbs
    if ($this->getThemeVariables('hide_breadcrumbs')){
      return "";
    }

    $theme_breadcrumbs_html = $this->getThemeVariables('breadcrumbs_html');
    if (!empty($theme_breadcrumbs_html)) {
      $this->setAttributes('breadcrumbs', 'large-breadcrumbs');
    }
    else {
      $this->setAttributes('breadcrumbs', 'default-breadcrumbs');
    }

    $Breadcrumb = $this->_getBreadcrumb();
    $crumb_list = $Breadcrumb->getCrumbList();

    // fetch key of the last element of the array.
    $crumb_last_key = $Breadcrumb->getCrumbCount() - 1;

    $breadcrumb_html = '<ol class="breadcrumb">';
    $request_uri = explode('/', $_SERVER['REQUEST_URI']);

    foreach ($crumb_list as $k => $v) {
      // add .active class to the last item of the breadcrumbs
      if ($k == $crumb_last_key) {
        if (count($request_uri) >= 3 && ($request_uri[2] != "" && $request_uri[2] != "index.php")) {
          $breadcrumb_html .= '<li class="active">' . $v->getText() . '</li>';
        }
      }
      else {
        $breadcrumb_html .= '<li><a href="' . $v->getURL() . '">' . $v->getText() . '</a></li>';
      }
    }
    $breadcrumb_html .= "</ol>";

    return <<<EOHTML
    <section{$this->getAttributes('breadcrumbs')}>
      <div class="container">
        <h3 class="sr-only">Breadcrumbs</h3>
        <div class="row">
          <div class="col-sm-16 padding-left-30">{$breadcrumb_html}</div>
          <div class="col-sm-8 margin-top-15">{$this->getShareButtonsHTML()}</div>
        </div>
        {$theme_breadcrumbs_html}
      </div>
    </section> <!-- /#breadcrumb -->
EOHTML;

  }

  /**
   * Set $Breadcrumb
   *
   * @param Breadcrumb $Breadcrumb
   */
  public function setBreadcrumb($Breadcrumb = NULL) {
    if (!$Breadcrumb instanceof Breadcrumb) {
      $App = $this->_getApp();
      if (!class_exists('Breadcrumb')) {
        require_once($App->getBasePath() . '/system/breadcrumbs.class.php');
      }
      $Breadcrumb = new Breadcrumb($this->getPageTitle());
    }
    $this->Breadcrumb = $Breadcrumb;
  }

  /**
   * Get Call For Action Button Html
   *
   * @return string
   */
  public function getCfaButton() {
    $settings = array(
      'hide' => FALSE,
      'html' => '',
      'class' => '',
      'href' => '',
      'text' => ''
    );

    $btn_cfa = $this->getThemeVariables('btn_cfa');
    $Breadcrumb = $this->_getBreadcrumb();
    $crumb1 = $Breadcrumb->getCrumbAt(1)->getText();
    $url_parts = explode('/', str_ireplace(array(
      'http://',
      'https://'
    ), '', $_SERVER['REQUEST_URI']));

    // If we are hidding the CFA Button.
     if ($crumb1 === 'Projects' && strtolower($url_parts[1]) != 'projects') {
      // If the user is not trying to override this button, let's change
      // it for all of our project websites.
      if (empty($btn_cfa['text']) && empty($btn_cfa['url'])) {
        $btn = array();
        $btn['btn_cfa']['text'] = '<i class="fa fa-star"></i> Donate';
        $btn['btn_cfa']['href'] = 'https://www.eclipse.org/donate/';
        $btn['btn_cfa']['class'] = 'btn btn-huge btn-info';
        $theme_variables = $this->setThemeVariables($btn);
        $btn_cfa = $theme_variables['btn_cfa'];
      }
    }

    if (!$btn_cfa) {
      $btn_cfa = array();
    }
    foreach ($btn_cfa as $key => $setting) {
      $type = 'string';
      if ($key == 'hide') {
        $type = 'bool';
      }
      $settings[$key] = $this->is_var($setting, $type);
    }

    $btn_cfa = array_merge($btn_cfa, $settings);

    if ($btn_cfa['hide']) {
      return "";
    }
    $cfa_default = $this->_getCfaButtonDefault();
    if (empty($btn_cfa['class'])) {
      $btn_cfa['class'] = $cfa_default['class'];
    }

    if (empty($btn_cfa['href'])) {
      $btn_cfa['href'] = $cfa_default['href'];
    }

    if (empty($btn_cfa['text'])) {
      $btn_cfa['text'] = $cfa_default['text'];
    }

    if (!empty($btn_cfa['html'])) {
      return $btn_cfa['html'];
    }

    $html = '<div' . $this->getAttributes('btn-call-for-action') . '><a href="' . $btn_cfa['href'] . '" class="' . $btn_cfa['class'] . '">';
    $html .= $btn_cfa['text'];
    $html .= '</a></div>';

    return $html;
  }

  /**
   * Get default variables for CFA
   *
   * @return array
   */
  protected function _getCfaButtonDefault() {
    $default['class'] = 'btn btn-huge btn-warning';
    $default['href'] = '//www.eclipse.org/downloads/';
    $default['text'] = '<i class="fa fa-download"></i> Download';
    return $default;
  }

  /**
   * Get Copyright Notice
   *
   * @return string
   */
  public function getCopyrightNotice() {
    return 'Copyright &copy; ' . date("Y") . ' The Eclipse Foundation. All Rights Reserved.';
  }

  /**
   * Get $Breadcrumb
   *
   * @return $Breadcrumb
   */
  protected function _getBreadcrumb() {
    if (!$this->Breadcrumb instanceof Breadcrumb) {
      $this->setBreadcrumb();
    }
    return $this->Breadcrumb;
  }

  /**
   * Get HTML for deprecated messages
   *
   * @return string
   */
  public function getDeprecatedMessage() {
    // Deprecated message
    $deprecated = "";
    $App = $this->_getApp();
    if ($App->getOutDated()) {
      $classes[] = "deprecated";
      $deprecated = '<div class="col-md-24"><div class="alert alert-danger" role="alert">';
      $deprecated .= $App->getOutDatedMessage();
      $deprecated .= '</div></div>';
    }
    return $deprecated;
  }

  /**
   * Get $diplay_more
   */
  public function getDisplayMore() {
    return $this->display_more;
  }


  /**
   * Set $diplay_more
   *
   * @param string $display
   */
  public function setDisplayMore($display = TRUE) {
    if ($display !== FALSE) {
      $display = TRUE;
    }
    $this->display_more = $display;
  }

  /**
   * Get $display_google_search
   */
  public function getDisplayGoogleSearch() {
    return $this->display_google_search;
  }

  /**
   * Set $display_google_search
   *
   * @param bool $display
   */
  public function setDisplayGoogleSearch($display = TRUE) {
    if ($display !== FALSE) {
      $display = TRUE;
    }
    $this->display_google_search = $display;
  }

  /**
   * Get $display_header_right
   */
  public function getDisplayHeaderRight() {
    return $this->display_header_right;
  }

  /**
   * Set $display_header_right
   *
   * @param bool $display
   */
  public function setDisplayHeaderRight($display = TRUE) {
    if ($display !== FALSE) {
      $display = TRUE;
    }
    $this->display_header_right = $display;
  }

  /**
   * Get $display_toolbar
   */
  public function getDisplayToolbar() {
    return $this->display_toolbar;
  }

  /**
   * Set $display_toolbar
   *
   * @param bool $display
   */
  public function setDisplayToolbar($display = TRUE) {
    if ($display !== FALSE) {
      $display = TRUE;
    }
    $this->display_toolbar = $display;
  }

  /**
   * Get Eclipse.org base URL
   *
   * @return string
   */
  public function getEclipseUrl() {
    $App = $this->_getApp();
    return $App->getWWWPrefix();
  }

  /**
   * Set Metatags
   *
   * @param string $key
   * @param array $body
   * @return boolean
   */
  public function setMetatags($key = '', $body = array()) {
    if (empty($key) || empty($body) || !is_array($body)) {
      return FALSE;
    }
    $this->metatags[$key] = $body;
    return TRUE;
  }

  /**
   * Get Metatags
   */
  public function getMetatags(){
    return $this->metatags;
  }

  /**
   * Get metatag by key
   *
   * @param string $property
   * @return boolean|mixed
   */
  public function getMetatagByKey($property = '') {
    if (!isset($this->metatags[$property])) {
      return FALSE;
    }
    return $this->metatags[$property];
  }

  /**
   * Get metatags HTML
   * @return string
   */
  public function getMetatagsHTML(){
    $metatags = $this->getMetatags();
    $html = "";
    foreach ($metatags as $key => $body) {
      $html .= '<meta';
      foreach ($body as $property => $value) {
        $html .= ' ' . $property . '="' . $value . '"';
      }
      $html .= '/>' . PHP_EOL;
    }
    return $html;
  }

  /**
   * Get $extra_headers output
   *
   * @return string
   */
  public function getExtraHeaders() {
    $App = $this->_getApp();
    $App->setOGTitle($this->getPageTitle());

    $styles_name = 'styles';
    switch ($this->getTheme()) {
      case 'locationtech':
        $styles_name = 'locationtech';
        break;

      case 'polarsys':
        $styles_name = 'polarsys';
        break;
    }

    $css = '<link rel="stylesheet" href="' . $this->getThemeUrl('solstice') . 'public/stylesheets/' . $styles_name . '.min.css"/>';

    $return = $css . PHP_EOL;

    // Add og:metatags if they haven't been set.
    // @todo: deprecated og functions in App().
    if (!$this->getMetatagByKey('og:description')) {
      $this->setMetatags('og:description', array(
        'property' => 'og:description',
        'content' => $App->getOGDescription(),
      ));
    }

    if (!$this->getMetatagByKey('og:image')) {
      $this->setMetatags('og:image', array(
        'property' => 'og:image',
        'content' => $App->getOGImage(),
      ));
    }

    if (!$this->getMetatagByKey('og:title')) {
      $this->setMetatags('og:title', array(
        'property' => 'og:title',
        'content' => $App->getOGTitle(),
      ));
    }

    if (!$this->getMetatagByKey('og:image:width')) {
      $this->setMetatags('og:image:width', array(
        'property' => 'og:image:width',
        'content' => $App->getOGImageWidth(),
      ));
    }

    if (!$this->getMetatagByKey('og:image:height')) {
      $this->setMetatags('og:image:height', array(
        'property' => 'og:image:height',
        'content' => $App->getOGImageHeight(),
      ));
    }

    $image = 'https://www.eclipse.org/eclipse.org-common/themes/solstice/public/images/logo/eclipse-400x400.png';

    // Schema.org markup for Google+
    if (!$this->getMetatagByKey('itemprop:name')) {
      $this->setMetatags('itemprop:name', array(
        'itemprop' => 'name',
        'content' => $App->getOGTitle(),
      ));
    }

    if (!$this->getMetatagByKey('itemprop:description')) {
      $this->setMetatags('itemprop:description', array(
        'itemprop' => 'description',
        'content' => $App->getOGDescription(),
      ));
    }

   if (!$this->getMetatagByKey('itemprop:image')) {
     $this->setMetatags('itemprop:image', array(
        'itemprop' => 'image',
        'content' => $image,
      ));
   }

    // Twitter Card data
   if (!$this->getMetatagByKey('twitter:site')) {
      $this->setMetatags('twitter:site', array(
        'name' => 'twitter:site',
        'content' => '@EclipseFdn',
      ));
    }

    if (!$this->getMetatagByKey('twitter:card')) {
      $this->setMetatags('twitter:card', array(
        'name' => 'twitter:card',
        'content' => 'summary',
      ));
    }

    if (!$this->getMetatagByKey('twitter:title')) {
      $this->setMetatags('twitter:title', array(
        'name' => 'twitter:title',
        'content' => $App->getOGTitle(),
      ));
    }

    if (!$this->getMetatagByKey('twitter:url')) {
      $this->setMetatags('twitter:url', array(
        'name' => 'twitter:url',
        'content' => $this->App->getCurrentURL(),
      ));
    }

    if (!$this->getMetatagByKey('twitter:description')) {
      $this->setMetatags('twitter:description', array(
        'name' => 'twitter:description',
        'content' => $App->getOGDescription(),
      ));
    }

    if (!$this->getMetatagByKey('twitter:image')) {
      $this->setMetatags('twitter:image', array(
        'name' => 'twitter:image',
        'content' => $image,
      ));
    }

    $return .= $this->getShareButtonsJS();
    $return .= $this->getMetatagsHTML();
    $return .= $this->extra_headers;
    $return .= $App->ExtraHtmlHeaders;

    // page-specific RSS feed
    if ($App->PageRSS != "") {
      if ($App->PageRSSTitle != "") {
        $App->PageRSSTitle = "Eclipse RSS Feed";
      }
      $return .= '<link rel="alternate" title="' . $App->PageRSSTitle . '" href="' . $App->PageRSS . '" type="application/rss+xml"/>';
    }

    return $return. PHP_EOL;
  }

  /**
   * Set $extra_headers
   *
   * @param string $headers
   */
  public function setExtraHeaders($headers = "") {
    $this->extra_headers = $headers;
  }

  /**
   * Get Html of Footer Region 1
   */
  public function getFooterRegion1() {
    return "";
  }

  /**
   * Get Html of Footer Region 2
   */
  public function getFooterRegion2() {
    return "";
  }

  /**
   * Get Html of Footer Region 3
   */
  public function getFooterRegion3() {
    return "";
  }

  /**
   * Get Html of Footer Region 4
   */
  public function getFooterRegion4() {
    return "";
  }

  /**
   * Get Html of Footer Region 5
   */
  public function getFooterRegion5() {
    return "";
  }

  /**
   * Get footer javascript code
   *
   * @return string
   */
  public function getExtraJsFooter() {
    $App = $this->_getApp();
    $return = $App->ExtraJSFooter . PHP_EOL;
    $return .= $this->getGoogleAnalytics() . PHP_EOL;
    return $return;
  }

  public function getHeaderRight(){
    if (!$this->getDisplayHeaderRight()) {
      return "";
    }
    return <<<EOHTML
      <div{$this->getAttributes('header-right')}>
        {$this->getGoogleSearch()}
        {$this->getCfaButton()}
      </div>
EOHTML;
  }

  public function getHeaderLeft(){
    return <<<EOHTML
      <div{$this->getAttributes('header-left')}>
        {$this->getLogo('default', TRUE)}
      </div>
EOHTML;
  }

  /**
   * Get Project Navigation html
   *
   * More information: https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108
   *
   * @return string
   */
  public function getHeaderNav() {
    $header_nav = $this->_getHeaderNavDefaultLogo();

    if (!$header_nav) {
      return "";
    }

    $html = "";
    $html .= '<div class="header_nav">';
    $html .= '<div class="col-xs-24 col-md-10 vcenter">';
    $logo = '<img src="' . $header_nav['logo']['src'] . '" alt="' . $header_nav['logo']['alt'] . '" class="img-responsive  header_nav_logo"/>';

    if (!empty($header_nav['logo']['url'])) {
      $html .= '<a href="' . $header_nav['logo']['url'] . '" title="' . $header_nav['logo']['alt'] . '" target="' . $header_nav['logo']['target'] . '">';
      $html .= $logo;
      $html .= '</a>';
    }
    else {
      $html .= $logo;
    }

    $html .= '</div>';
    $html .= '<div class="col-xs-24 col-md-offset-2 col-md-12 vcenter">';
    $html .= '<ul class="clearfix">';

    foreach ($header_nav['links'] as $l) {
      $html .= '<li class="col-xs-24 col-md-12">';
      $html .= '<a class="row" href="' . $l['url'] . '" title="' . $l['link_title'] . '" target="' . $l['target'] . '">';
      $html .= '<i class="col-xs-3 col-md-6 fa ' . $l['icon'] . '"></i>';
      $html .= '<span class="col-xs-21 c col-md-17">';
      $html .= $l['title'];
      $html .= '<p>' . $l['text'] . '</p>';
      $html .= '</span>';
      $html .= '</a>';
      $html .= '</li>';
    }

    $html .= '</ul>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
  }

  /**
   * Add default values for the links
   *
   * Return FALSE if requirements are not met.
   *
   * @param array $l
   * @return boolean|array
   */
  private function _getHeaderNavDefaultLinks($l) {

    foreach ($l as &$link) {
      $link = $this->is_var($link);
    }

    if (empty($l['url']) || empty($l['title']) || empty($l['icon'])) {
      return FALSE;
    }

    $l['link_title'] = $l['title'];
    if (!empty($l['text'])) {
      $l['link_title'] .= ': ' . $l['text'];
    }

    $default = array(
      'icon' => '',
      'url' => '',
      'title' => '',
      'target' => '_self',
      'text' => '',
      'link_title' => ''
    );

    return array_merge($default, $l);
  }

  /**
   * Add default values for the logo
   *
   * Return FALSE if requirements are not met.
   *
   * @return boolean|array
   */
  private function _getHeaderNavDefaultLogo() {
    $h = $this->getThemeVariables('header_nav');

    if (!is_array($h) || empty($h['logo']) || !is_array($h['logo'])) {
      return FALSE;
    }

    $links = array();
    $count = 1;
    $default = array(
      'links' => array(),
      'logo' => array(
        'src' => '',
        'alt' => '',
        'url' => '',
        'target' => '_self'
      )
    );

    foreach ($h['logo'] as &$logo) {
      $logo = $this->is_var($logo);
    }

    $h['logo'] = array_merge($default['logo'], $h['logo']);

    if (empty($h['logo']['src']) || empty($h['links']) || !is_array($h['links'])) {
      return FALSE;
    }

    foreach ($h['links'] as $l) {
      $link = $this->_getHeaderNavDefaultLinks($l);
      if ($link && $count <= 6) {
        $count++;
        $links[] = $link;
      }
    }

    $h['links'] = $links;
    if (empty($h['links'])) {
      return FALSE;
    }

    return $h;
  }

  /**
   * Get Google Analytics JS code
   *
   * @return string
   */
  public function getGoogleAnalytics() {
    if (empty($this->ga_code) && !is_null($this->ga_code)) {
      $this->setGoogleAnalytics();
    }

    if (is_null($this->ga_code)) {
      return "";
    }

    return <<<EOHTML
      <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '$this->ga_code']);
        _gaq.push(['_trackPageview']);

        (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

      </script>
EOHTML;
  }

  /**
   * Set google analytics id code
   *
   * @param string $code
   */
  public function setGoogleAnalytics($code = "") {
    $App = $this->_getApp();
    if (empty($code)) {
      $code = $App->getGoogleAnalyticsTrackingCode();
    }
    $this->ga_code = $code;
  }

  /**
   * Get Google Search HTML
   */
  public function getGoogleSearch() {
    if (!$this->getDisplayGoogleSearch()) {
      return "";
    }
    $domain = $this->App->getEclipseDomain();
    return <<<EOHTML
    <div class="row"><div class="col-md-24">
    <div id="custom-search-form" class="reset-box-sizing">
    <script>
      (function() {
        var cx = '011805775785170369411:p3ec0igo0qq';
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//cse.google.com/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
      })();
    </script>
    <gcse:searchbox-only gname="main" resultsUrl="https://{$domain['domain']}/home/search.php"></gcse:searchbox-only>
    </div></div></div>
EOHTML;
  }

  /**
   * Set page Html
   *
   * @param string $html
   */
  public function setHtml($html = "") {
    if (is_string($html)) {
      $this->html = $html;
    }
  }

  /**
   * Get page $html
   */
  public function getHtml() {
    return $this->html;
  }

  /**
   * Get $layout
   */
  public function getLayout() {
    if (empty($this->layout)) {
      $this->setLayout();
    }
    return $this->layout;
  }

  /**
   * Set $layout
   *
   * @param string $layout
   */
  public function setLayout($layout = "") {
    $acceptable_layouts = array(
      'default',
      'default-header',
      'default-footer',
      'barebone',
      'thin',
      'thin-header',
      'default-with-footer-min',
      'thin-with-footer-min'
    );
    $this->layout = 'default';
    if (in_array($layout, $acceptable_layouts)) {
      $this->layout = $layout;
    }
  }

  /**
   * Get logo
   *
   * Fetch a specfic logo from $logos
   *
   * @param string $id
   *
   * @return string
   */
  public function getLogo($id = '', $link = FALSE) {
    // Logos
    $link_logo = $this->getAttributes('link_logo_' . $id);
    $img_logo = $this->getAttributes('img_logo_' . $id);
    $return = "";
    if (!empty($img_logo)) {
      $att_id = str_replace('_', '-', 'logo-' . $id);
      $this->setAttributes($att_id, 'wrapper-' . $att_id);
      //$logo['white_link'] = '<a href="' . $base_url . '">' . $logo['white'] . '</a>';
      $return = '<div' . $this->getAttributes($att_id) . '>';

      if ($link) {
        $url = $this->getBaseUrl();
        if (is_string($link)) {
          $url =  $link;
          $this->setAttributes('link_logo_' . $id, $url, 'href');
          $logo_updated = TRUE;
        }
        $link_logo_href = $this->getAttributes('link_logo_' . $id, 'href');
        if (empty($link_logo_href)) {
          $this->setAttributes('link_logo_' . $id, $url, 'href');
          $logo_updated = TRUE;
        }
        $return .= '<a'. $this->getAttributes('link_logo_' . $id) . '>';
      }


      $return .= '<img' . $this->getAttributes('img_logo_' . $id). '/>';
      if (isset($logo_updated) && $logo_updated === TRUE) {
        $return .= '</a>';
      }
      $return .= '</div>';
    }
    return $return;
  }

  /**
   * Add associative array of logos
   *
   * @param array $logos
   */
  public function setLogo($logos = array()) {
    $this->logos = array_merge($this->logos, $logos);
  }

  /**
   * Get $Menu
   */
  protected function _getMenu() {
    if (!$this->Menu instanceof Menu) {
      $this->setMenu();
    }
    return $this->Menu;
  }

  /**
   * Set $Menu
   *
   * @param unknown $Menu
   */
  public function setMenu($Menu = NULL) {
    if (!$Menu instanceof Menu) {
      $App = $this->_getApp();
      if (!class_exists('Menu')) {
        require_once($App->getBasePath() . '/system/menu.class.php');
      }
      $Menu = new Menu();
    }
    $this->Menu = $Menu;
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
      // Menu did not change, let's set the default Solstice menu.
      $Menu = $this->_getMenuDefault();
      $main_menu = $Menu->getMenuArray();
      $default_menu_flag = TRUE;
    }

    // Main-menu
    foreach ($main_menu as $item) {
      $menu_li_classes = "";
      $caption = $item->getText();
      if ($default_menu_flag && $caption == 'Download') {
        $menu_li_classes = ' class="visible-thin"';
      }
      $items[] = '<li' . $menu_li_classes . '><a href="' . $item->getURL() . '" target="' . $item->getTarget() . '">' . $caption . '</a></li>';
    }

    return implode($items, '');
  }

  /**
   * Get Default solstice Menu()
   *
   * @return Menu
   */
  protected function _getMenuDefault() {
    $base_url = $this->getBaseUrl();
    $App = $this->_getApp();
    if (!class_exists('Menu')) {
      require_once ($App->getBasePath() . '/system/menu.class.php');
    }
    $Menu = new Menu();
    $Menu->setMenuItemList(array());
    $Menu->addMenuItem("Download", $base_url . "downloads/", "_self");
    $Menu->addMenuItem("Getting Started", $base_url . "users/", "_self");
    $Menu->addMenuItem("Members", $base_url . "membership/", "_self");
    $Menu->addMenuItem("Projects", $base_url . "projects/", "_self");
    return $Menu;
  }

  /**
   * Get the "More Menu"
   *
   * @param string $id
   *
   * @return string
   */
  public function getMoreMenu($id = 'desktop') {
    $allowed_menu = array(
      'desktop',
      'mobile'
    );
    if (!in_array($id, $allowed_menu)) {
      return '';
    }

    $more_menu = $this->_getMoreMenu();
    $variables['mobile'] = "";
    $variables['desktop'] = '';
    foreach ($more_menu as $key => $value) {
      $first = TRUE;
      foreach ($value as $link) {
        if ($first) {
          $first = FALSE;
          $variables['desktop'] .= '<ul class="col-sm-8 list-unstyled"><li><p><strong>' . $key . '</strong></p></li>';
          $variables['mobile'] .= '<li class="dropdown visible-xs"><a href="#" data-toggle="dropdown" class="dropdown-toggle">' . $key . ' <b class="caret"></b></a><ul class="dropdown-menu">';
        }
        $l = '<li><a href="' . $link['url'] . '">' . $link['caption'] . '</a></li>';
        $variables['desktop'] .= $l;
        $variables['mobile'] .= $l;
      }
      $variables['mobile'] .= '</ul></li>';
      $variables['desktop'] .= '</ul>';
    }
    return $variables[$id];
  }

  /**
   * Get more menu array
   *
   * @return @array
   */
  protected function _getMoreMenu() {
    $variables = array();
    $base_url = $this->getBaseUrl();
    $variables['Community'][] = array(
      'url' => 'http://marketplace.eclipse.org',
      'caption' => 'Marketplace'
    );

    $variables['Community'][] = array(
      'url' => 'http://events.eclipse.org',
      'caption' => 'Events'
    );

    $variables['Community'][] = array(
      'url' => 'http://www.planeteclipse.org/',
      'caption' => 'Planet Eclipse'
    );

    $variables['Community'][] = array(
      'url' => $base_url . 'community/eclipse_newsletter/',
      'caption' => 'Newsletter'
    );

    $variables['Community'][] = array(
      'url' => 'https://www.youtube.com/user/EclipseFdn',
      'caption' => 'Videos'
    );

    $variables['Participate'][] = array(
      'url' => 'https://bugs.eclipse.org/bugs/',
      'caption' => 'Report a Bug'
    );

    $variables['Participate'][] = array(
      'url' => $base_url . 'forums/',
      'caption' => 'Forums'
    );

    $variables['Participate'][] = array(
      'url' => $base_url . 'mail/',
      'caption' => 'Mailing Lists'
    );

    $variables['Participate'][] = array(
      'url' => 'https://wiki.eclipse.org/',
      'caption' => 'Wiki'
    );

    $variables['Participate'][] = array(
      'url' => 'https://wiki.eclipse.org/IRC',
      'caption' => 'IRC'
    );

    $variables['Participate'][] = array(
      'url' => $base_url . 'contribute/',
      'caption' => 'How to Contribute'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://iot.eclipse.org',
      'caption' => 'Internet of Things'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://locationtech.org',
      'caption' => 'LocationTech'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://lts.eclipse.org',
      'caption' => 'Long-Term Support'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://polarsys.org',
      'caption' => 'PolarSys'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://science.eclipse.org',
      'caption' => 'Science'
    );

    $variables['Working Groups'][] = array(
      'url' => 'http://www.openmdm.org',
      'caption' => 'OpenMDM'
    );
    return $variables;
  }

  /**
   * Get Navigation variables
   *
   * @return array
   */
  public function getNav() {
    // Nav menu
    $base_url = $this->getBaseUrl();
    $variables = array(
      '#items' => array(),
      'link_count' => 0,
      'img_separator' => '<img src="' . $base_url . 'public/images/template/separator.png"/>',
      'html_block' => ''
    );
    if ($this->Nav instanceof Nav) {
      // add faux class to #novaContent
      $this->setAttributes('main_container_classes', 'background-image-none');
      $variables['link_count'] = $this->Nav->getLinkCount();
      $variables['html_block'] = $this->Nav->getHTMLBlock();
      for ($i = 0; $i < $variables['link_count']; $i++) {
        $variables['#items'][] = $this->Nav->getLinkAt($i);
      }
    }

    return $variables;
  }

  /**
   * Set $Nav
   *
   * @param Nav $Nav
   */
  public function setNav($Nav = NULL) {
    if ($Nav instanceof Nav) {
      $this->Nav = $Nav;
    }
  }

  /**
   * Get $page_author
   *
   * @return string
   */
  public function getPageAuthor() {
    if (empty($this->page_author)) {
      $this->page_author = 'Christopher Guindon';
    }
    return $this->page_author;
  }

  /**
   * Set $page_author
   *
   * @param string $author
   */
  public function setPageAuthor($author) {
    $this->page_author = $author;
  }

  /**
   * Get $page_keywords
   *
   * @return string
   */
  public function getPageKeywords() {
    if (empty($this->page_keywords)) {
      $this->page_keywords = 'eclipse,project,plug-ins,plugins,java,ide,swt,refactoring,free java ide,tools,platform,open source,development environment,development,ide';
    }
    return $this->page_keywords;
  }

  /**
   * Set $page_keywords
   *
   * @param string $page_keywords
   */
  public function setPageKeywords($page_keywords) {
    $this->page_keywords = $page_keywords;
  }

  /**
   * Get $page_title
   *
   * @return string
   */
  public function getPageTitle() {
    if (empty($this->page_title)) {
      $this->page_title = 'Eclipse - The Eclipse Foundation open source community website';
    }
    return $this->page_title;
  }

  /**
   * Set $page_title
   *
   * @param string $title
   */
  public function setPageTitle($title) {
    $this->page_title = strip_tags($title);
  }

  /**
   * Get $promo_html html output
   *
   * @return string
   */
  public function getPromoHtml() {
    if (is_null($this->promo_html)) {
      $this->setPromoHtml();
    }
    if (empty($this->promo_html)) {
      return '';
    }

    return '<div class="container"><div class="col-md-24">' . $this->promo_html . '</div></div>';
  }

  /**
   * Set $promo_html
   *
   * @param string $html
   */
  public function setPromoHtml($html = "") {
    if (!empty($html) && is_string($html)) {
      $this->promo_html = $html;
      return TRUE;
    }
    $App = $this->_getApp();
    $theme = $this->getTheme();
    ob_start();
    if ($App->Promotion == TRUE) {
      if ($App->CustomPromotionPath != "") {
        include ($App->CustomPromotionPath);
      }
      else {
        include ($App->getPromotionPath($theme));
      }
    }
    $this->promo_html = trim(ob_get_clean());
  }

  /**
   * Get $Session
   *
   * @return Session
   */
  protected function _getSession() {
    if (!$this->Session instanceof Session) {
      $this->_setSession();
    }
    return $this->Session;
  }

  /**
   * Set $Session
   *
   * @param Session $Session
   */
  protected function _setSession($Session = NULL) {
    if (!$Session instanceof Session) {
      $App = $this->_getApp();
      $Session = $App->useSession();
    }
    $this->Session = $Session;
    $this->session_variables = array();
  }

  /**
   * Returns a Take me back
   *
   * @return string
   */
  private function _getTakeMeBack() {
    // Return an empty string if we're on the dev.eclipse.org website
    if (strpos($_SERVER['SERVER_NAME'], "dev.eclipse") !== FALSE) {
      return "";
    }

    $path = parse_url($this->App->getCurrentURL(), PHP_URL_PATH);
    if (substr($path, 0, 1) == "/") {
      $path = substr($path, 1);
    }
    $url = urlencode($this->getBaseUrl() . $path);
    return "?takemeback=" . $url;
  }

  /**
   * Get $ession_variables
   *
   * @param string $id
   *
   * @return string
   */
  public function getSessionVariables($id = "") {
    if (empty($this->session_variables)) {
      $this->session_variables['session'] = array(
        'Friend' => NULL,
        'name' => '',
        'last_name' => ''
      );
      $Session = $this->_getSession();
      $Friend = $Session->getFriend();
      $this->session_variables['create_account_link'] = '<a href="' . $this->getBaseUrlLogin() . '/user/register"><i class="fa fa-user fa-fw"></i> Create account</a>';
      $this->session_variables['my_account_link'] = '<a href="' . $this->getBaseUrlLogin() . '/user/login/' . $this->_getTakeMeBack() . '"><i class="fa fa-sign-in fa-fw"></i> Log in</a>';
      $this->session_variables['logout'] = '';

      if ($Session->isLoggedIn()) {
        $this->session_variables['user_ldap_uid'] = $Friend->getUID();
        $this->session_variables['name'] = $Friend->getFirstName();
        $this->session_variables['last_name'] = $Friend->getLastName();
        $this->session_variables['full_name'] = $this->App->checkPlain($this->session_variables['name'] . ' ' . $this->session_variables['last_name']);
        $this->session_variables['create_account_link'] = 'Welcome, ' . $this->session_variables['full_name'];
        if (!empty($this->session_variables['user_ldap_uid'])){
           $this->session_variables['create_account_link'] = '<a href="https://www.eclipse.org/user/' . $this->session_variables['user_ldap_uid'] . '">Welcome, ' . $this->session_variables['full_name'] . '</a>';
        }
        $this->session_variables['my_account_link'] = '<a href="' . $this->getBaseUrlLogin() . '/user/edit" class="" data-tab-destination="tab-profile"><i class="fa fa-edit fa-fw"></i> Edit my account</a>';
        // Adding <li> with logout because we only display
        // two options if the user is not logged in.
        $this->session_variables['logout'] = '<li><a href="' . $this->getBaseUrlLogin() . '/user/logout"><i class="fa fa-power-off fa-fw"></i> Log out</a></li>';
      }
    }
    if (!empty($this->session_variables[$id])) {
      return $this->session_variables[$id];
    }
    return '';
  }

  /**
   * Get HTML for System Messages
   *
   * @return string
   */
  public function getSystemMessages() {
    // System messages
    $variables['sys_messages'] = "";
    $App = $this->_getApp();
    if ($sys_messages = $App->getSystemMessage()) {
      $main_container_classes = $this->getAttributes('main_container_classes', 'class');
      $container_classes_array = explode(' ', $main_container_classes);
      $sys_classes = 'clearfix';
      // Verify if the system message is included in a .container class.
      // If not, wrap the system message in a container.
      if (!in_array('container', $container_classes_array)) {
        $sys_classes .= ' container';
      }
      $variables['sys_messages'] = '<div id="sys_message" class="' . $sys_classes . '">';
      $variables['sys_messages'] .= '<div class="row"><div class="col-md-24">' . $sys_messages . '</div></div>';
      $variables['sys_messages'] .= '</div>';
    }

    return $variables['sys_messages'];
  }

  /**
   * Get $theme
   *
   * @return string
   */
  protected function getTheme() {
    return $this->theme;
  }

  /**
   * Set $theme
   *
   * @param string $theme
   */
  protected function setTheme($theme) {
    $this->theme = $theme;
  }

  /**
   * Get array of theme files if they exist
   */
  public function getThemeFiles() {
    $App = $this->_getApp();
    $eclipse_org_common_root = $App->getBasePath();

    $files = array();
    $files['header'] = $eclipse_org_common_root . '/themes/solstice/header.php';
    $files['menu'] = $eclipse_org_common_root . '/themes/solstice/menu.php';
    $files['nav'] = $eclipse_org_common_root . '/themes/solstice/nav.php';
    $files['body'] = $eclipse_org_common_root . '/themes/solstice/body.php';
    $files['main_menu'] = $eclipse_org_common_root . '/themes/solstice/main_menu.php';
    $files['footer'] = $eclipse_org_common_root . '/themes/solstice/footer.php';
    $files['footer-min'] = $eclipse_org_common_root . '/themes/solstice/footer-min.php';

    // Validate theme files
    foreach ($files as $key => $template_files) {
      if (!file_exists($template_files)) {
        unset($files[$key]);
      }
    }

    return $files;
  }

  /**
   * Generate page with theme
   */
  public function generatePage() {
    $btn_cfa = $this->getThemeVariables('btn_cfa');
    if (!empty($btn_cfa['hide']) && $btn_cfa['hide'] === TRUE) {
      $this->setAttributes('body', 'hidden-cfa-button');
    }

    $promo_html = $this->getPromoHtml();
    if (!empty($promo_html) && !empty($theme_variables['btn_cfa']['hide_breadcrumbs'])) {
      $this->setAttributes('body', "no-breadcrumbs-with-promo");
    }

    ob_start();
    switch ($this->getLayout()) {
      case 'barebone':
        $this->setAttributes('header-wrapper', 'barebone-layout');
        $this->setAttributes('header-wrapper', 'thin-header');
        $this->setAttributes('header-row', 'header-row', 'id');
        $this->resetAttributes('header-row', 'class');
        $this->setAttributes('header-row', 'row-fluid');
        $this->setDisplayToolbar(FALSE);
        $this->setDisplayGoogleSearch(FALSE);
        $this->resetAttributes('header-left', 'class');
        $this->setAttributes('header-left', 'col-sm-8 col-md-6 col-lg-4');
        $this->resetAttributes('header-container', 'class');
        $this->setAttributes('header-container', 'container-fluid');
        $this->resetAttributes('main-menu-wrapper', 'class');
        $this->setAttributes('main-menu-wrapper', 'col-sm-16 col-md-18 col-lg-20');
        $this->setAttributes('main-menu-ul-navbar', 'navbar-right');
        $this->setDisplayHeaderRight(FALSE);
        print $this->getBareboneAssets();
        print $this->getThemeFile('menu');
        break;

      case 'thin':
        $this->setAttributes('header-wrapper', 'thin-header');
        $this->resetAttributes('header-left', 'class');
        $this->setAttributes('header-left', 'col-sm-6 col-md-6 col-lg-5');
        $this->resetAttributes('main-menu-wrapper', 'class');
        $this->setAttributes('main-menu-wrapper', 'col-sm-18 col-md-18 col-lg-19');
        $this->setAttributes('main-menu-ul-navbar', 'navbar-right');
        $this->setAttributes('header-row', 'row');

        $this->setDisplayHeaderRight(FALSE);
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        print $this->getThemeFile('body');
        print $this->getThemeFile('footer');
        break;

      case 'thin-header':
        $this->setAttributes('header-wrapper', 'thin-header');
        $this->resetAttributes('header-left', 'class');
        $this->setAttributes('header-left', 'col-sm-6 col-md-6 col-lg-5');
        $this->resetAttributes('main-menu-wrapper', 'class');
        $this->setAttributes('main-menu-wrapper', 'col-sm-18 col-md-18 col-lg-19');
        $this->setAttributes('main-menu', 'navbar-right');
        $this->setAttributes('header-row', 'row');
        $this->setDisplayHeaderRight(FALSE);
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        break;

      case 'thin-with-footer-min':
        $this->setAttributes('header-wrapper', 'thin-header');
        $this->resetAttributes('header-left', 'class');
        $this->setAttributes('header-left', 'col-sm-6 col-md-6 col-lg-5');
        $this->resetAttributes('main-menu-wrapper', 'class');
        $this->setAttributes('main-menu-wrapper', 'col-sm-18 col-md-18 col-lg-19');
        $this->setAttributes('main-menu', 'navbar-right');
        $this->setAttributes('header-row', 'row');
        $this->setDisplayHeaderRight(FALSE);
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        print $this->getThemeFile('body');
        print $this->getThemeFile('footer-min');
        break;

      case 'default-header':
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        break;

      case 'default-with-footer-min':
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        print $this->getThemeFile('body');
        print $this->getThemeFile('footer-min');
        break;

      case 'default':
        print $this->getThemeFile('header');
        print $this->getThemeFile('menu');
        print $this->getThemeFile('body');
        print $this->getThemeFile('footer');
        break;

      case 'default-footer':
        print $this->getThemeFile('footer');
        break;

    }
    return ob_flush();
  }

  /**
   * Get HTML of theme file
   *
   * @param string $id
   */
  public function getThemeFile($id = "") {
    $files = $this->getThemeFiles();

    ob_start();
    if (!empty($files[$id])) {
      include ($files[$id]);
    }
    $html = ob_get_clean();

    if ($html) {
      return $html;
    }
    return "";
  }

  /**
   * Get absolute path of theme
   *
   * @param string $theme
   *
   * @return string
   */
  public function getThemeUrl($theme = '') {
    if (empty($theme)) {
      $theme = $this->getTheme();
    }
    return '/eclipse.org-common/themes/' . $theme . '/';
  }

  /**
   * Get $theme_varaibles
   *
   * @param string $id
   *
   * @return string
   */
  public function getThemeVariables($id = '') {
    $App = $this->_getApp();
    $this->theme_variables = $App->getThemeVariables();

    $this->setAttributes('main-container', $this->theme_variables['main_container_classes']);

    $this->setAttributes('body', $this->theme_variables['body_classes']);

    if (!empty($id) && isset($this->theme_variables[$id])) {
      return $this->theme_variables[$id];
    }
    return '';
  }

  /**
   * Set $theme_variables
   *
   * @param array $variables
   *
   * @return array
   */
  public function setThemeVariables($variables = array()) {
    $App = $this->_getApp();
    $App->setThemeVariables($variables);
    $this->theme_variables = $App->getThemeVariables();

    return $this->theme_variables;
  }

  public function getToolbarHtml() {
    if (!$this->getDisplayToolbar()) {
      return "";
    }
    return <<<EOHTML
    <div{$this->getAttributes("toolbar-container-wrapper")}>
      <div{$this->getAttributes("toolbar-container")}>
        <div{$this->getAttributes("toolbar-row")}>
          <div{$this->getAttributes("toolbar-user-links")}>
            <ul class="list-inline">
              <li>{$this->getSessionVariables('create_account_link')}</li>
              <li>{$this->getSessionVariables('my_account_link')}</li>
              {$this->getSessionVariables('logout')}
            </ul>
          </div>
        </div>
      </div>
    </div>
EOHTML;
  }

  /**
   * Validate variable
   *
   * Return an empty variable if argument is empty or
   * wrong type.
   *
   * @param mixed $var
   * @param string $type
   *
   * @return string
   */
  public function is_var($var, $type = 'string') {
    switch ($type) {
      case 'string':
        if (!empty($var) && is_string($var)) {
          return $var;
        }
        return "";
        break;

      case 'array':
        if (!empty($var) && is_array($var)) {
          return $var;
        }
        return array();
        break;

      case 'bool':
        if (!empty($var) && is_bool($var)) {
          return $var;
        }
        return FALSE;
        break;
    }
  }

  /**
   * Get Html of Header Top
   */
  public function getHeaderTop() {
    return "";
  }

  /**
   * JS Script Settings
   * @return string
   */
  public function getScriptSettings() {

    $cookie_name = 'eclipse_settings';

    // Keep only the majob and minor version
    $php_version = substr(phpversion(), 0, 3);

    // Remove the dot separating the major and minor version
    $php_version = str_replace(".", "", $php_version);

    // The Cookie class is enabled by default
    $cookie_enabled = 1;

    // If the PHP version is lower than 5.3
    // We need to disable the cookie class
    if ($php_version < "53") {
      $cookie_enabled = 0;
    }

    $script_array = array(
      "settings" => array(
        "cookies_class" => array(
          "name" => $cookie_name,
          "enabled" => $cookie_enabled,
        ),
      ),
    );

    return "<script> var eclipse_org_common = ". json_encode($script_array) ."</script>";
  }
}