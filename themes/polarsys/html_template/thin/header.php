<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

  require_once('../app.php');
  require_once('../../header.php');
?>
  <?php if (isset($_GET['site_login'])) :?>
    <div id="toolbar-container-wrapper" class="clearfix">
      <div class="container">
        <div id="row-toolbar" class="text-right hidden-print">
          <div id="row-toolbar-col" class="col-md-24">
            <ul class="list-inline">
              <li><?php print $variables['session']['create_account_link'];?></li>
              <li><?php print $variables['session']['my_account_link'];?></li>
              <?php print $variables['session']['logout'];?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  <?php endif;?>
    <header role="banner" id="page-header-logo">
      <div class="container">
        <div id="row-logo-search" class="clearfix">
          <div id="header-left" class="hidden-xs col-sm-8">
            <?php print $variables['logo']['default_link'];?>
          </div>
          <div id="header-right" class="form-inline col-sm-10 pull-right col-md-8 col-lg-5 hidden-print hidden-xs">
            <div id="header-right-container">
              <?php print $SolsticeBtnCfa->build();?>
            </div>
          </div>
          <div id="main-menu" class="navbar yamm col-md-16">
            <div id="navbar-collapse-1" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <?php print $variables['menu']['main_menu']; ?>
              </ul>
            </div>
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <?php print $variables['logo']['mobile_link'];?>
            </div>
          </div>
        </div>
      </div>
    </header>
    <?php if (!$variables['theme_variables']['hide_breadcrumbs']) :?>
      <section id="breadcrumb" class="<?php print $variables['theme_variables']['breadcrumbs_classes'];?>">
        <div class="container">
          <?php print $variables['breadcrumbs'];?>
          <?php print $variables['theme_variables']['breadcrumbs_html'];?>
          </div>
        </section>
      <?php endif; ?>
    <?php print $variables['promotion']['desktop'];?>
    <main role="main">
      <div class="<?php print $variables['theme_variables']['main_container_classes'];?>" id="novaContent">
          <?php print $variables['deprecated'];?>

