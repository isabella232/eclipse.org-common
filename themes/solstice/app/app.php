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

function solstice_variables(&$variables) {

	global $App;

	$Nav =  $variables['page']['Nav'];
	$Menu =  $variables['page']['Menu'];

	// If the main menu is custom, do not change it
	$NewMenu = new $Menu();
	$main_menu = $Menu->getMenuArray();
	if($NewMenu->getMenuArray() == $main_menu){
		$Menu = new $Menu();
	  $Menu->setMenuItemList(array());
	  $Menu->addMenuItem("Getting Started ", "/users/", "_self");
	  $Menu->addMenuItem("Members", "/membership/", "_self");
	  $Menu->addMenuItem("Projects", "/projects/", "_self");
	  $main_menu = $Menu->getMenuArray();
	}

	$theme = $variables['page']['theme'];
	$variables['theme_variables'] = $App->getThemeVariables();
	if (!empty($variables['theme_variables']['breadcrumbs_html'])) {
		$variables['theme_variables']['breadcrumbs_classes'] = 'large-breadcrumbs';
	} else {
		$variables['theme_variables']['breadcrumbs_classes'] = 'defaut-breadcrumbs';
	}
	$variables['url'] = $App->getWWWPrefix() . '/';

	$classes = array();
	$deprecated = "";
	$items = array();

	$variables['App'] = $App;
	$variables['theme_url'] = '/eclipse.org-common/themes/solstice/';

	$variables['page']['extra_js_footer'] = $App->ExtraJSFooter;

	// HTML headers
	$variables['head']['og_title'] = $App->getOGTitle();
	$variables['head']['og_description'] = $App->getOGDescription();
	$variables['head']['og_image'] = $App->getOGImage();

	// Deprecated message
	if ($App->getOutDated()) {
		$classes[] =  "deprecated";
		$deprecated = '<div class="message-box-container">';
		$deprecated .= '<div class="message-box error">This page is deprecated and may contain some information that is no longer relevant or accurate.</div>';
		$deprecated .= '</div>';
	}
	$variables['deprecated'] =  $deprecated;

	// Body
	$variables['body']['classes'] = implode($classes, ' ');
	$variables['body']['id'] = 'body_solstice';

	// Logos
	$variables['logo']['default'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-beta.png" alt="Eclipse.org logo" width="213" class="logo-eclipse-default"/>';
	$variables['logo']['white'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-logo-bw-800x188.png" alt="Eclipse.org black and white logo" width="166" height="39" class="logo-eclipse-white"/>';

	// Main-menu
	foreach ($main_menu as $item) {
		$items[] = '<li><a href="' . $item->getURL() .'" target="' . $item->getTarget() .'">' . $item->getText() . '</a></li>';
	}
	$variables['menu']['main_menu'] = implode($items, '');


	// Nav menu
	if ($Nav != NULL) {
	  $variables['menu']['nav']['link_count'] = $Nav->getLinkCount();
	  $variables['menu']['nav']['img_separator'] = '<img src="' . $variables['theme_url'] . 'public/images/template/separator.png"/>';

	  for ($i = 0; $i < $variables['menu']['nav']['link_count']; $i++) {
	    $variables['menu']['nav']['#items'][] = $Nav->getLinkAt($i);
	  }
	}

	// Ads and promotions
	ob_start();
	if ($App->Promotion == TRUE && $App->CustomPromotionPath != "") {
	  include($App->CustomPromotionPath);
	} else if ($App->Promotion == TRUE) {
	  include($App->getPromotionPath($theme));
	} else {
	  print '<a href="//www.eclipse.org/">' . $variables['logo']['default'] . '</a>';
	}
	$variables['promotion'] = ob_get_clean();

	$variables['uri'] = parse_url($_SERVER['REQUEST_URI']);

	// FOR TESTING ONLY,
	$variables['promotion'] = '<a href="' . $variables['url'] . '">' . $variables['logo']['default'] . '</a>';

	// Eclipse Copyright
	$variables['footer']['copyright'] = 'Copyright &copy; ' . date("Y") . ' The Eclipse Foundation. All Rights Reserved.';
}

$variables = array();
$variables['page']['author'] = $pageAuthor;
$variables['page']['keywords'] = $pageKeywords;
$variables['page']['title'] = $pageTitle;
$variables['page']['theme'] = $theme;
$variables['page']['Nav'] = $Nav;
$variables['page']['Menu'] = $Menu;
$variables['page']['html'] = $html;
$variables['page']['extra_headers'] = (isset($extraHtmlHeaders)) ? $extraHtmlHeaders : "";

solstice_variables($variables);

//print_r($variables);
