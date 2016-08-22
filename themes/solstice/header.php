<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="<?php print $this->getPageAuthor(); ?>"/>
    <meta name="keywords" content="<?php print $this->getPageKeywords(); ?>"/>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,700,300,600,100" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="<?php print $this->getThemeUrl(); ?>public/images/favicon.ico"/>
    <title><?php print $this->getPageTitle(); ?></title>
    <?php print $this->getExtraHeaders();?>
    <?php print $this->getScriptSettings(); ?>
  </head>
  <body<?php print $this->getAttributes('body');?>>
    <a class="sr-only" href="#content">Skip to main content</a>
    <?php print $this->getHeaderTop(); ?>
