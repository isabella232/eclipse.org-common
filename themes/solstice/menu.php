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
    <header role="banner">
      <div class="container">
        <div id="row-toolbar" class="text-right hidden-print">
          <div id="row-toolbar-col" class="col-md-24">
            <ul class="list-inline">
              <li><?php print $Theme->getSessionVariables('create_account_link');?></li>
              <li><?php print $Theme->getSessionVariables('my_account_link');?></li>
              <?php print $Theme->getSessionVariables('logout');?>
            </ul>
          </div>
        </div>
        <div id="row-logo-search">
          <div id="header-left" class="col-sm-14 col-md-16 col-lg-19">
            <div class="row">
              <div class="hidden-xs">
                <?php print $Theme->getLogo('default_link');?>
              </div>
               <div id="main-menu" class="navbar row yamm">
                <div id="navbar-collapse-1" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                    <?php print $Theme->getMenu()?>
                    <?php print $Theme->getMoreMenu('mobile')?>
                    <!-- More -->
                    <li class="dropdown hidden-xs"><a data-toggle="dropdown" class="dropdown-toggle">More<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li>
                          <!-- Content container to add padding -->
                          <div class="yamm-content">
                            <div class="row">
                              <?php print $Theme->getMoreMenu('desktop')?>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </li>
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
                  <?php print $Theme->getLogo('mobile_link');?>
                </div>
              </div>
            </div>
          </div>
          <div id="header-right" class="form-inline col-sm-10 col-md-8 col-lg-5 hidden-print hidden-xs">
            <div id="header-right-container">
              <div id="custom-search-form" class="reset-box-sizing">
                <?php print $Theme->getGoogleSearch();?>
              </div><!-- /input-group -->
              <?php print $Theme->getCfaButton();?>
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
