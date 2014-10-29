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
require_once('SolsticeBase.class.php');

class SolsticeBtnCfa extends SolsticeBase{

  var $btn_cfa = array();

  /**
   * Constructor
   */
  function __construct($variables = array()) {
    if (!empty($variables) && is_array($variables)) {
      $this->btn_cfa = $variables;
    }
  }

  /**
   * Add default values
   *
   * Return bool
   *
   * @return boolean
   */
  private function _cfa_default() {
    $settings = array(
      'hide' => FALSE,
      'html' => '',
      'class' => '',
      'href' => '',
      'text' => ''
    );

    foreach ($this->btn_cfa as $key => $setting) {
      $type = 'string';
      if ($key == 'hide') {
        $type = 'bool';
      }
      $settings[$key] = $this->is_var($setting, $type);
    }

    $this->btn_cfa = array_merge($this->btn_cfa, $settings);

    if ($this->btn_cfa['hide']){
      return FALSE;
    }

    if (empty($this->btn_cfa['class'])){
      $this->btn_cfa['class'] = 'btn btn-huge btn-warning';
    }

    if (empty($this->btn_cfa['href'])) {
      $this->btn_cfa['href'] = '//www.eclipse.org/downloads/';
    }

    if (empty($this->btn_cfa['text']) ){
      $this->btn_cfa['text'] = '<i class="fa fa-download"></i> Download';
    }

    return TRUE;
  }

  /**
   * Build CFA button
   * More information: https://bugs.eclipse.org/bugs/show_bug.cgi?id=447799
   *
   * @return string
   */
  function build() {
    if (!empty($this->btn_cfa['html'])) {
      return $this->btn_cfa['html'];
    }

    if (!$this->_cfa_default()) {
      return "";
    }

    $html = '<a id="btn-call-for-action" href="' . $this->btn_cfa['href'] . '" class="' . $this->btn_cfa['class'] . '">';
    $html .= $this->btn_cfa['text'];
    $html .= '</a>';

    return $html;

  }
}