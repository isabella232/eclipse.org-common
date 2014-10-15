<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
class SolsticeHeaderNav {

  private $header_nav = array();

  /**
   * Constructor
   */
  function __construct($variables = array()) {
    if (!empty($variables) && is_array($variables)) {
      $this->header_nav = $variables;
    }
  }

  /**
   * Add default values for the links
   *
   * Return FALSE if requirements are not met.
   *
   * @param array $l
   * @return boolean|array
   */
  private function _header_nav_default_links($l) {

    foreach ($l as &$link) {
      $link = $this->_is_string($link);
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
      'link_title' => '',
    );

    return array_merge($default, $l);
  }

  /**
   * Validate String Variable
   *
   * Return an empty string if argument is not a string.
   *
   * @param string $var
   * @return string
   */
  private function _is_string($var = "") {
    if (!empty($var) && is_string($var)) {
      return $var;
    }
    return "";
  }

  /**
   * Add default values for the logo
   *
   * Return FALSE if requirements are not met.
   *
   * @return boolean|array
   */
  private function _header_nav_default_logo() {
    $h = $this->header_nav;
    if (!is_array($h) || empty($h['logo']) || !is_array($h['logo'])){
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
        'target' => '_self',
      ),
    );

    foreach ($h['logo'] as &$logo) {
      $logo = $this->_is_string($logo);
    }

    $h['logo'] = array_merge($default['logo'], $h['logo']);

    if (empty($h['logo']['src']) || empty($h['links']) || !is_array($h['links'])) {
      return FALSE;
    }

    foreach ($h['links'] as $l) {
      $link = $this->_header_nav_default_links($l);
      if ($link && $count <= 4){
        $count++;
        $links[] = $link;
      }
    }

    $h['links'] = $links;
    if (empty($h['links'])){
      return FALSE;
    }

  return $h;
  }

  /**
   * Build Project Navigation
   * More information: https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108
   *
   * @return string
   */
  public function build() {
    $this->header_nav = $this->_header_nav_default_logo();

    if (!$this->header_nav) {
      return "";
    }

    $html = "";
    $html .= '<div class="header_nav">';
    $html .= '<div class="col-xs-24 col-md-10 vcenter">';
    $logo = '<img src="' . $this->header_nav['logo']['src'] . '" alt="' . $this->header_nav['logo']['alt'] . '" class="img-responsive  header_nav_logo"/>';

    if (!empty($this->header_nav['logo']['url'])) {
      $html .= '<a href="' . $this->header_nav['logo']['url'] . '" title="' . $this->header_nav['logo']['alt'] . '" target="' . $this->header_nav['logo']['target'] . '">';
      $html .=  $logo;
      $html .= '</a>';
    }
    else {
      $html .= $logo;
    }

    $html .= '</div>';
    $html .= '<div class="col-xs-24 col-md-offset-2 col-md-12 vcenter">';
    $html .= '<ul class="clearfix">';

    foreach ($this->header_nav['links'] as $l) {
      $html .= '<li class="col-xs-24 col-md-12">';
      $html .= '<a class="row" href="' . $l['url'] .'" title="' . $l['link_title'] .'" target="' . $l['target'] .'">';
      $html .= '<i class="col-xs-3 col-md-6 fa ' . $l['icon'] .'"></i>';
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
}
