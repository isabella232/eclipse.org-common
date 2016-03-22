<?php
/**
 * *****************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

if (!isset($theme)) {
  $theme = 'soltice';
}

if ($this instanceof App) {
  $Theme = $this->getThemeClass($theme);
  $Theme->setApp($this);
}

if (!is_a($Theme, 'baseTheme')) {
  if (!($App instanceof App)) {
    require_once (realpath(dirname(__FILE__) . '/../../system/app.class.php'));
    $App = new App();
  }
  $Theme = $App->getThemeClass($theme);
  $Theme->setApp($App);
}

if ($Breadcrumb instanceof Breadcrumb) {
  $Theme->setBreadcrumb($Breadcrumb);
}

if ($Nav instanceof Nav) {
  $Theme->setNav($Nav);
}

if ($Menu instanceof Menu) {
  $Theme->setMenu($Menu);
}

if (isset($pageAuthor)) {
  $Theme->setPageAuthor($pageAuthor);
}

if (isset($pageKeywords)) {
  $Theme->setPageKeywords($pageKeywords);
}

if (isset($pageTitle)) {
  $Theme->setPageTitle($pageTitle);
}

if (isset($extraHtmlHeaders)) {
  $Theme->setExtraHeaders($extraHtmlHeaders);
}
