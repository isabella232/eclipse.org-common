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

?>

<div id="toolbar-container-wrapper" class="clearfix">
  <div class="container no-border">
    <div id="row-toolbar" class="region region-toolbar text-right hidden-print solstice-region-element-count-1 row">
      <div id="row-toolbar-col" class="col-md-24">
        <section id="block-solstice-support-solstice-support-toolbar" class="block block-solstice-support block-region-toolbar block-solstice-support-toolbar clearfix">
          <div class="block-content">
            <ul class="list-inline">
              <li><?php print $Theme->getSessionVariables('create_account_link');?></li>
              <li><?php print $Theme->getSessionVariables('my_account_link');?></li>
              <?php print $Theme->getSessionVariables('logout');?>
            </ul>
          </div>
        </section>
        <!-- /.block -->
      </div>
    </div>
  </div>
</div>

<header id="page-header-logo" class="" role="banner">
  <div class="container no-border">
    <div id="row-logo-search" class="row">
      <div id="header-left" class="hidden-xs col-sm-8">
        <?php print $Theme->getLogo('default_link');?>
      </div>
      <div id="header-right" class="hidden-xs form-inline col-sm-16 pull-right">
        <div class="row">
          <div class="col-md-9 col-sm-12 pull-right">
            <?php print $Theme->getCfaButton();?>
          </div>
        </div>
      </div>
      <div id="main-menu" class="navbar yamm col-sm-24">
        <div id="navbar-collapse-1" class="navbar-collapse collapse">
          <div class="region region-navigation solstice-region-element-count-1">
            <section id="block-system-main-menu" class="block block-system block-menu block-region-navigation block-main-menu clearfix">
              <div class="block-content">
                <ul class="menu nav navbar-nav">
                  <?php print $Theme->getMenu()?>
                </ul>
              </div>
            </section>
            <!-- /.block -->
          </div>
        </div>
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <?php print $Theme->getLogo('mobile_link');?>
        </div>
      </div>
    </div>
  </div>
</header>

<?php if (!$Theme->getThemeVariables('hide_breadcrumbs')) :?>
  <section id="breadcrumb" class="<?php print $Theme->getAttributes('breadcrumbs');?>">
    <div class="container">
    <h3 class="sr-only">Breadcrumbs</h3>
      <div class="<?php print $Theme->getAttributes('breadcrumbs_wrapper');?>">
        <?php print $Theme->getBreadcrumbHtml();?>
      </div>
      <?php print $Theme->getThemeVariables('breadcrumbs_html')?>
      </div>
    </section>
<?php endif; ?>

<?php print $Theme->getPromoHtml();?>

<main role="main" class="<?php print $Theme->getAttributes('main');?>">
  <div class="<?php print $Theme->getAttributes('main_container');?>" id="novaContent">
    <?php print $Theme->getDeprecatedMessage();?>
    <?php print $Theme->getSystemMessages();?>
    <?php print $Theme->getThemeVariables('main_container_html');?>
    <?php print $Theme->getHeaderNav();?>
