<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *     Eclipse Foundation - initial API and implementation
 *******************************************************************************/
$www_prefix = "";
global $App;
if (isset ( $App )) {
  $www_prefix = $App->getWWWPrefix ();
}
$theme = "polarsys";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">
<html lang="en" dir="ltr" version="HTML+RDFa 1.1"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dc="http://purl.org/dc/terms/"
  xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:og="http://ogp.me/ns#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:sioc="http://rdfs.org/sioc/ns#"
  xmlns:sioct="http://rdfs.org/sioc/types#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:xsd="http://www.w3.org/2001/XMLSchema#">
<head profile="http://www.w3.org/1999/xhtml/vocab">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"
  content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
<title><?php print $pageTitle ?></title>
<meta name="author" content="<?php print $pageAuthor ?>" />
<meta name="keywords" content="<?php print $pageKeywords ?>" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/bootstrap.css"
  media="all" />
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/global.css"
  media="all" />
<!--[if (lt IE 9)&(!IEMobile)]>
<link rel="stylesheet" type="text/css" href="/eclipse.org-common/themes/<?php print $theme ?>/css/a.css" media="all"/>
<![endif]-->

<!--[if gte IE 9]><!-->
<style type="text/css"
  media="all and (min-width: 740px) and (min-device-width: 740px), (max-device-width: 800px) and (min-width: 740px) and (orientation:landscape)"></style>
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/b.css" media="all" />
<!--<![endif]-->

<!--[if gte IE 9]><!-->
<style type="text/css"
  media="all and (min-width: 980px) and (min-device-width: 980px), all and (max-device-width: 1024px) and (min-width: 1024px) and (orientation:landscape)"></style>
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/c.css" media="all" />
<!--<![endif]-->
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/polarsys-alpha-default.css"
  media="all" />
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/polarsys-alpha-default-normal.css"
  media="all" />
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/polarsys-alpha-default-wide.css"
  media="all" />
<link rel="stylesheet" type="text/css"
  href="/eclipse.org-common/themes/<?php print $theme ?>/css/polarsys-alpha-default-narrow.css"
  media="all" />

  <?php if( isset($extraHtmlHeaders) ) echo $extraHtmlHeaders; ?>
</head>
<body class="html front not-logged-in">
