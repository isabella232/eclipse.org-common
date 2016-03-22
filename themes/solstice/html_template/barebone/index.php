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
require_once(realpath(dirname(__FILE__) . '/../app.php'));
?>
<!--  START OF SOLSTICE HEADER -->
<style type="text/css">
@import
  url('//eclipse.org/eclipse.org-common/themes/solstice/public/stylesheets/barebone<?php if (isset($_GET['mobile'])) : print '-toolbar';endif;?>.min.css');
</style>
<script
  src="//eclipse.org/eclipse.org-common/themes/solstice/public/javascript/barebone.min.js"></script>

<div class="thin-header barebone-layout">
  <header role="banner">
    <div class="container-fluid">
      <?php if (isset($_GET['site_login'])) :?>
        <div id="row-toolbar" class="text-right hidden-print">
          <div id="row-toolbar-col" class="col-md-24">
            <ul class="list-inline">
              <li><?php print $Theme->getSessionVariables('create_account_link');?></li>
              <li><?php print $Theme->getSessionVariables('my_account_link');?></li>
            </ul>
          </div>
        </div>
      <?php endif;?>
        <div id="row-logo-search">
        <div id="header-left">
          <div class="row">
            <?php if (!isset($_GET['mobile'])) :?>
            <div class="hidden-xs col-sm-6 reset">
                <?php print $Theme->getLogo('default_link');?>
            </div>
            <?php endif;?>
            <div id="main-menu" class="navbar col-sm-18 yamm reset">
              <div id="navbar-collapse-1" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                  <?php print $Theme->getMenu()?>
                  <?php print $Theme->getMoreMenu('mobile')?>
                  <?php if (!isset($_GET['mobile'])) :?>
                  <!-- More -->
                  <li class="dropdown hidden-xs"><a data-toggle="dropdown"
                    class="dropdown-toggle">More<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li>
                        <!-- Content container to add padding -->
                        <div class="yamm-content">
                          <div class="row">
                              <?php print $Theme->getMoreMenu('desktop')?>
                            </div>
                        </div>
                      </li>
                    </ul></li>
                  <?php endif;?>
                </ul>
              </div>
              <div class="navbar-header">
                <button type="button" class="navbar-toggle"
                  data-toggle="collapse" data-target="#navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <?php if (isset($_GET['mobile'])) :?>
                  <?php print $Theme->getLogo('mobile_link_always_show');?>
                <?php else :?>
                  <?php print $Theme->getLogo('mobile_link');?>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <?php if (isset($_GET['breadcrumbs'])) :?>
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
  <?php endif; ?>
</div>
<!--  END OF SOLSTICE HEADER -->
