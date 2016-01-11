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

?>
    <header role="banner">
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
        <div id="row-logo-search">
          <div id="header-left" class="col-sm-14 col-md-16 col-lg-19">
            <div class="row">
              <div class="hidden-xs">
                <?php print $variables['logo']['default_link'];?>
              </div>
               <div id="main-menu" class="navbar row yamm">
                <div id="navbar-collapse-1" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                    <?php print $variables['menu']['main_menu']; ?>
                    <?php print $variables['menu']['mobile_more'];?>
                    <!-- More -->
                    <li class="dropdown hidden-xs"><a data-toggle="dropdown" class="dropdown-toggle">More<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li>
                          <!-- Content container to add padding -->
                          <div class="yamm-content">
                            <div class="row">
                              <?php print $variables['menu']['desktop_more'];?>
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
                  <?php print $variables['logo']['mobile_link'];?>
                </div>
              </div>
            </div>
          </div>
          <div id="header-right" class="form-inline col-sm-10 col-md-8 col-lg-5 hidden-print hidden-xs">
            <div id="header-right-container">
              <div id="custom-search-form" class="reset-box-sizing">
                <script>
                  (function() {
                    var cx = '011805775785170369411:15ipmpflp-0';
                    var gcse = document.createElement('script');
                    gcse.type = 'text/javascript';
                    gcse.async = true;
                    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                        '//cse.google.com/cse.js?cx=' + cx;
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(gcse, s);
                  })();
                </script>
                <gcse:searchbox-only></gcse:searchbox-only>
              </div><!-- /input-group -->
              <?php print $SolsticeBtnCfa->build();?>
            </div>
          </div>
        </div>
      </div>
    </header>
    <?php if (!$variables['theme_variables']['hide_breadcrumbs']) :?>
      <section id="breadcrumb" class="<?php print $variables['theme_variables']['breadcrumbs_classes'];?>">
        <div class="container">
        <h3 class="sr-only">Breadcrumbs</h3>
          <div class="<?php print $variables['theme_variables']['breadcrumbs_wrapper_classes'];?>">
            <?php print $variables['breadcrumbs'];?>
          </div>
          <?php print $variables['theme_variables']['breadcrumbs_html'];?>
          </div>
        </section>
      <?php endif; ?>
    <?php print $variables['promotion']['desktop'];?>
    <main role="main" class="<?php print $variables['main_classes'];?>">
      <div class="<?php print $variables['theme_variables']['main_container_classes'];?>" id="novaContent">
          <?php print $variables['deprecated'];?>
          <div class="container padding-top-25">
            <div class="col-md-24"><?php print $this->getSystemMessage(); ?></div>
          </div>
          <?php print $variables['theme_variables']['main_container_html'];?>
          <?php print $SolsticeHeaderNav->build();?>
