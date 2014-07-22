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
require_once ('../app.php');
// require_once('head.php');
?>
<!--  START OF SOLSTICE HEADER -->
<style type="text/css">
@import
  url('//eclipse.org/eclipse.org-common/themes/solstice/public/stylesheets/barebone.min.css');
</style>
<script
  src="//eclipse.org/eclipse.org-common/themes/solstice/public/javascript/barebone.min.js"></script>

<div class="thin-header">
  <header role="banner">
    <div class="container-fluid">
      <?php if (isset($_GET['site_login'])) :?>
      <div id="row-toolbar" class="text-right hidden-print">
        <div id="row-toolbar-col" class="col-md-24">
          <ul class="list-inline">
            <li><?php print $variables['session']['create_account_link'];?></li>
            <li><?php print $variables['session']['my_account_link'];?></li>
          </ul>
        </div>
      </div>
        <?php endif;?>
        <div id="row-logo-search">
        <div id="header-left">
          <div class="row">
            <div class="hidden-xs col-sm-6 reset">
                <?php print $variables['promotion'];?>
              </div>
            <div id="main-menu" class="navbar col-sm-18 yamm reset">
              <div id="navbar-collapse-1" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <?php print $variables['menu']['main_menu']; ?>
                    <?php print $variables['menu']['mobile_more'];?>
                    <!-- More -->
                  <li class="dropdown hidden-xs"><a data-toggle="dropdown"
                    class="dropdown-toggle">More<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li>
                        <!-- Content container to add padding -->
                        <div class="yamm-content">
                          <div class="row">
                              <?php print $variables['menu']['desktop_more'];?>
                            </div>
                        </div>
                      </li>
                    </ul></li>
                </ul>
              </div>
              <div class="navbar-header">
                <button type="button" class="navbar-toggle"
                  data-toggle="collapse" data-target="#navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                  <?php print $variables['logo_mobile']; ?>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <?php if (isset($_GET['breadcrumbs'])) :?>
  <section id="breadcrumb"
    class="<?php print $variables['theme_variables']['breadcrumbs_classes'];?>">
    <div class="container-fluid">
      <?php print $variables['breadcrumbs'];?>
       <?php print $variables['theme_variables']['breadcrumbs_html'];?>
    </div>
  </section>
  <?php endif; ?>
</div>
<!--  END OF SOLSTICE HEADER -->
