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

function supernova_variables(&$variables) {

	global $App;

	$Nav =  $variables['page']['Nav'];
	$Menu =  $variables['page']['Menu'];
	$classes = array();
	$deprecated = "";
	$items = array();

	$variables['App'] = $App;
	$variables['theme_url'] = '/eclipse.org-common/themes/supernova/';

	// HTML headers
	$variables['head']['og_title'] = $App->getOGTitle();
	$variables['head']['og_description'] = $App->getOGDescription();
	$variables['head']['og_image'] = $App->getOGImage();
	$variables['head']['extra_headers'] = (isset($extraHtmlHeaders)) ? $extraHtmlHeaders : "";

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
	$variables['body']['id'] = 'body_supernova';

	// Logos
	$variables['logo']['default'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-800x188.png" alt="Eclipse.org logo" width="213" height="50"/>';
	$variables['logo']['white'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-logo-bw-800x188.png" alt="Eclipse.org black and white logo" width="166" height="39"/>';

	// Main-menu
	if ($Menu != NULL) {
		for ($i = 0; $i < $Menu->getMenuItemCount(); $i++) {
			$item = $Menu->getMenuItemAt($i);
			$items[] = '<li><a href="' . $item->getURL() .'" target="' . $item->getTarget() .'">' . $item->getText() . '</a></li>';
		}
		$variables['menu']['main_menu'] = implode($items, '');
	}

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
supernova_variables($variables);

//print_r($variables);
