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

  require_once(realpath(dirname(__FILE__) . '/../../../system/app.class.php'));
  $App = new App();

  $Theme = $App->getThemeClass($App->getHTTPParameter('theme'));
  $Theme->setPageAuthor("Christopher Guindon");
  $Theme->setPageKeywords("eclipse.org, Eclipse Foundation");
  $Theme->setPageTitle('HTML Template');
  $Theme->setHtml('<h1>HTML Template</h1>');
  $Theme->setLayout($App->getHTTPParameter('layout'));
  $Theme->generatePage();

