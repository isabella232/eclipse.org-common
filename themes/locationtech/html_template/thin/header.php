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

  require_once('../app.php');
  $Theme->setAttributes('body', 'thin-layout');
  require_once('../../header.php');
?>

    <header role="banner" id="page-header-logo">
      <div class="container">
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
        <div id="row-logo-search" class="row">
          <div id="header-left">
            <div class="row">
              <div class="hidden-xs col-sm-6 padding-top-20">
                <?php print $Theme->getLogo('default_link');?>
              </div>
              <div id="main-menu" class="navbar yamm ">
                <div id="navbar-collapse-1" class="navbar-collapse collapse">
                  <div class="region region-navigation solstice-region-element-count-1">
                    <section id="block-system-main-menu" class="block block-system block-menu block-region-navigation block-main-menu clearfix">
                      <div class="block-content">
                        <ul class="menu nav navbar-nav">
                          <li class="first expanded dropdown">
                            <a href="https://www.locationtech.org/list-of-projects" title="List of Projects" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Technology <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                              <li class="first leaf"><a href="https://www.locationtech.org/list-of-projects" title="List of Projects">View Projects</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/proposals/propose-new-technology" title="Basic instructions for creating a technology project">Create a Proposal</a></li>
                              <li class="last leaf"><a href="https://www.locationtech.org/proposals" title="List of project proposals">Proposals</a></li>
                            </ul>
                          </li>
                          <li class="expanded dropdown">
                            <a href="https://www.locationtech.org/members" title="Members" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Members <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                              <li class="first leaf"><a href="https://www.locationtech.org/members-list" title="Members">View Members</a></li>
                              <li class="last leaf"><a href="https://www.locationtech.org/content/become-member" title="Instructions for Joining LocationTech as a Member">Become a Member</a></li>
                            </ul>
                          </li>
                          <li class="leaf"><a href="http://tour.locationtech.org/" title="The 2015 LocationTech Tour">Tour 2016</a></li>
                          <li class="leaf"><a href="https://www.locationtech.org/meetings" title="Meetings">Meetings</a></li>
                          <li class="leaf"><a href="https://www.locationtech.org/events" title="LocationTech events">Events</a></li>
                          <li class="leaf"><a href="https://www.locationtech.org/steeringcommittee">Steering Committee</a></li>
                          <li class="last expanded dropdown">
                            <a href="https://www.locationtech.org/about" title="About LocationTech" data-target="#" class="dropdown-toggle" data-toggle="dropdown">About Us <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                              <li class="first leaf"><a href="https://www.locationtech.org/charter" title="LocationTech Charter">Charter</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/news" title="News">News</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/community_news" title="Community News">Community News</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/about" title="Read a bit more about us.">About Us</a></li>
                              <li class="leaf"><a href="http://www.eclipse.org/org/foundation/staff.php" title="See a list of the staff who provide services to support the community and ecosystem">Staff</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/conduct">Community Code of Conduct</a></li>
                              <li class="leaf"><a href="https://www.locationtech.org/faq" title="">FAQ</a></li>
                              <li class="last leaf"><a href="https://www.locationtech.org/jobs">Jobs</a></li>
                            </ul>
                          </li>
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
        </div>

      </div>
    </header>
    <?php if (!$variables['theme_variables']['hide_breadcrumbs']) :?>
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
    <?php print $Theme->getPromoHtml();?>
    <main role="main" class="<?php print $Theme->getAttributes('main');?>">
      <div class="<?php print $Theme->getAttributes('main_container');?>" id="novaContent">
          <?php print $Theme->getDeprecatedMessage();?>
          <?php print $Theme->getSystemMessages();?>
          <?php print $Theme->getThemeVariables('main_container_html');?>
          <?php print $Theme->getHeaderNav();?>

