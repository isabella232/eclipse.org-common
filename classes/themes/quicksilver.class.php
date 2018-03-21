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

    $this->setAttributes('header-left', 'col-sm-7 col-md-5');
    $this->setAttributes('main-menu-wrapper', 'col-sm-24 col-md-14 reset');
    $this->setAttributes('main-menu-wrapper-no-header-right', 'col-sm-24 col-md-19 reset');
    $this->setAttributes('header-right', 'col-sm-7 col-md-5 text-right hidden-print hidden-xs pull-right');


    $this->setAttributes('navbar-main-menu', 'float-right');
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
      $this->setAttributes('main-menu-wrapper', 'col-sm-17 col-md-19 reset');
      return "";
    }
  }

}
