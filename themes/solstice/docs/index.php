<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php");


  $App   = new App();
  $Nav  = new Nav();
  $Menu   = new Menu();

  # Begin: page-specific settings.  Change these.
  $pageTitle     = " How to use the Eclipse Solstice theme";
  $pageKeywords  = "eclipse solstice";
  $pageAuthor    = "Christopher Guindon";

  $theme = 'solstice';
  $allowed_themes = array('polarsys', 'locationtech', 'solstice');
  $_theme = $App->getHTTPParameter('theme');
  if (in_array($_theme, $allowed_themes)) {
    $theme = $_theme;
  }
  $Theme = $App->getThemeClass($theme);

  // Place your html content in a file called content/en_pagename.php
  ob_start();
  include("content/en_" . $App->getScriptName());
  $html = ob_get_clean();

  $variables['btn_cfa'] = array(
    'hide' => FALSE, // Optional - Hide the CFA button.
    'html' => '', // Optional - Replace CFA html and insert custom HTML.
    'class' => 'btn btn-huge btn-info', // Optional - Replace class on CFA link.
    'href' => '/eclipse.org-common/themes/solstice/docs/starterkit/solstice-starterkit.zip', // Optional - Replace href on CFA link.
    'text' => '<i class="fa fa-download"></i> Download StarterKit' // Optional - Replace text of CFA link.
  );

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);

  $App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="//eclipse.org/orion/editor/releases/5.0/built-editor.css"/>');
  $App->AddExtraHtmlHeader('<script src="//eclipse.org/orion/editor/releases/5.0/built-editor.min.js"></script>');
  $App->AddExtraHtmlHeader('<script>
  /*global require*/
  require(["orion/editor/edit"], function(edit) {
    edit({className: "editor"});
  });
</script>');

  $App->generatePage($theme, $Menu, NULL, $pageAuthor, $pageKeywords, $pageTitle, $html);

