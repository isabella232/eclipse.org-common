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
  $base_url = '//staging.eclipse.org/';
	$Nav =  $variables['page']['Nav'];
	$Menu =  $variables['page']['Menu'];
  $Breadcrumb = $variables['page']['Breadcrumb'];

	// Breadcrumbs
	$crumb_list = $Breadcrumb->getCrumbList();

  // fetch key of the last element of the array.
  $crumb_last_key = $Breadcrumb->getCrumbCount()-1;

  $variables['breadcrumbs'] = '<ol class="breadcrumb">';
	foreach ($crumb_list as $k => $v) {
		// add .active class to the last item of the breadcrumbs
		if($k == $crumb_last_key) {
			$variables['breadcrumbs'] .= '<li class="active">' . $v->getText() . '</li>';
		}
		else {
			$variables['breadcrumbs'] .= '<li><a href="' . $v->getURL() . '">' . $v->getText() . '</a></li>';
		}
	}
	$variables['breadcrumbs'] .= "</ol>";

	// If the main menu is custom, do not change it
	$NewMenu = new $Menu();
	$main_menu = $Menu->getMenuArray();
	if ($NewMenu->getMenuArray() == $main_menu) {
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
		$variables['theme_variables']['breadcrumbs_classes'] = 'large-breadcrumbs hidden-print';
	} else {
		$variables['theme_variables']['breadcrumbs_classes'] = 'defaut-breadcrumbs hidden-print';
	}
	$variables['url'] = '/';

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
	$variables['logo']['mobile'] = '<img src="' . $variables['theme_url'] . 'public/images/logo/eclipse-beta.png" alt="Eclipse.org logo" width="137" class="logo-eclipse-default"/>';

	// Main-menu
	foreach ($main_menu as $item) {
		$items[] = '<li><a href="' . $item->getURL() .'" target="' . $item->getTarget() .'">' . $item->getText() . '</a></li>';
	}

	$variables['menu']['main_menu'] = implode($items, '');

	$variables['menu']['more'] = array();

	$variables['menu']['more']['Community'][] = array(
			'url' => 'http://events.eclipse.org',
			'caption' => 'Events'
	);

	$variables['menu']['more']['Community'][] = array(
			'url' => $variables['url'] . 'forums/',
			'caption' => 'Forums'
	);

	$variables['menu']['more']['Community'][] = array(
			'url' => '//www.planeteclipse.org/',
			'caption' => 'Planet Eclipse'
	);

  /*
	$variables['menu']['more']['Community'][] = array(
		'url' => '//download.eclipse.org',
		'caption' => 'Download Eclipse'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => 'http://help.eclipse.org',
		'caption' => 'Documentation'
	);

	$variables['menu']['more']['Community'][] = array(
		'url' => $variables['url'] . 'projects/',
		'caption' => 'Projects'
	);
*/
	$variables['menu']['more']['Community'][] = array(
	  'url' => $variables['url'] . 'community/eclipse_newsletter/',
		'caption' => 'Newsletter'
	);


  $variables['menu']['more']['Working Groups'][] = array(
  	'url' => 'http://wiki.eclipse.org/Auto_IWG',
  	'caption' => 'Automotive'
  );

  $variables['menu']['more']['Working Groups'][] = array(
  		'url' => 'http://iot.eclipse.org',
  		'caption' => 'Internet of Things'
  );

  $variables['menu']['more']['Working Groups'][] = array(
  		'url' => 'http://locationtech.org',
  		'caption' => 'LocationTech'
  );

  $variables['menu']['more']['Working Groups'][] = array(
  		'url' => 'http://lts.eclipse.org',
  		'caption' => 'Long-Term Support'
  );

  $variables['menu']['more']['Working Groups'][] = array(
  		'url' => 'http://polarsys.org',
  		'caption' => 'PolarSys'
  );


  $variables['menu']['more']['Explore'][] = array(
  		'url' => 'http://marketplace.eclipse.org',
  		'caption' => 'Marketplace'
  );
  $variables['menu']['more']['Explore'][] = array(
  		'url' => 'https://bugs.eclipse.org/bugs/',
  		'caption' => 'Bugzilla'
  );

  $variables['menu']['more']['Explore'][] = array(
  		'url' => '//wiki.eclipse.org/',
  		'caption' => 'Wiki'
  );


  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'legal/epl-v10.html',
  		'caption' => 'Eclipse Public License'
  );

  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'legal/privacy.php',
  		'caption' => 'Privacy Policy'
  );
  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'legal/termsofuse.php',
  		'caption' => 'Terms of Use'
  );
  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'legal/copyright.php',
  		'caption' => 'Copyright Agent'
  );
  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'legal/',
  		'caption' => 'Legal'
  );


  /*
  $variables['menu']['more']['Legal'][] = array(
  		'url' => $variables['url'] . 'org/foundation/contact.php',
  		'caption' => 'Contact Us'
  );
  */

  $variables['menu']['mobile_more'] = "";
  $variables['menu']['desktop_more'] = '';
  foreach ($variables['menu']['more'] as $key => $value) {
  	$first = TRUE;
  	foreach ($value as $link){
  		if ($first) {
  			$first = FALSE;
  			$variables['menu']['desktop_more'] .= '<ul class="col-sm-6 list-unstyled"><li><p><strong>' . $key . '</strong></p></li>';
  			$variables['menu']['mobile_more'] .= '<li class="dropdown visible-xs"><a href="#" data-toggle="dropdown" class="dropdown-toggle">' . $key . ' <b class="caret"></b></a><ul class="dropdown-menu">';
  		}
  		$l = '<li><a href="' . $link['url'] . '">' . $link['caption'] . '</a></li>';
  		$variables['menu']['desktop_more'] .= $l;
  		$variables['menu']['mobile_more'] .= $l;
  	}
  	$variables['menu']['mobile_more'] .= '</ul></li>';
  	$variables['menu']['desktop_more'] .= '</ul>';
  }

	// Nav menu
	if ($Nav != NULL) {
		// add faux class to #novaContent
		$variables['theme_variables']['main_container_classes'] .= " background-image-none";

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
	  print '<a href="' . $variables['url'] . '">' . $variables['logo']['default'] . '</a>';
	}
	$variables['promotion'] = ob_get_clean();
	$variables['logo_mobile'] =  '<a href="' . $variables['url'] . '" class="navbar-brand visible-xs">' . $variables['logo']['mobile'] . '</a>';

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
$variables['page']['Breadcrumb'] = $Breadcrumb;
$variables['page']['extra_headers'] = (isset($extraHtmlHeaders)) ? $extraHtmlHeaders : "";

solstice_variables($variables);

//print_r($variables);
