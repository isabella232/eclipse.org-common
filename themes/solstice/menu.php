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
			  <div class="row hidden-xs" id="row-logo-search">
				  <div id="logo" class="col-sm-16">
	          <?php print $variables['promotion'];?>
				  </div>
				  <div id="search" class="col-sm-8">
				    <a target="_blank" class="btn btn-sm btn-default call-for-action" href="https://www.surveymonkey.com/s/eclipsedesign">
	            Submit feedback
	          </a>
					  <form action="//www.google.com/cse" id="form-eclipse-search" role="form" class="form-inline">
						  <fieldset class="form-group">
							  <input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
							  <input id="search-box" type="text" name="q" size="25" class="form-control"/>
							  <input id="search-button" type="submit" name="sa" value="Search" class="btn btn-default"/>
						  </fieldset>
					  </form>
					  <script type="text/javascript" src="//www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>
				  </div>
	      </div>
	      <div class="row" id="row-nav-links">
		      <!-- Demo navbar -->
			    <div id="main-menu" class="navbar yamm col-sm-18 col-md-15">
			        <div class="navbar-header">
					      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					      </button>
					       <a class="navbar-brand visible-xs" href="#">Eclipse</a>
    					</div>
			        <div id="navbar-collapse-1" class="navbar-collapse collapse">
			          <ul class="nav navbar-nav">
			            <?php print $variables['menu']['main_menu']; ?>
			            <!-- Classic list -->
			            <li class="dropdown hidden-xs"><a href="#" data-toggle="dropdown" class="dropdown-toggle">More<b class="caret"></b></a>
			              <ul class="dropdown-menu">
			                <li>
			                  <!-- Content container to add padding -->
			                  <div class="yamm-content">
			                    <div class="row">
			                      <ul class="col-sm-6 list-unstyled">
			                        <li>
			                          <p><strong>Getting Started</strong></p>
			                        </li>
			                        <li><a href="//download.eclipse.org">Download Eclipse</a></li>
															<li><a href="//help.eclipse.org">Documentation</a></li>
															<li><a href="<?php print $variables['url']; ?>community/eclipse_newsletter/">Newsletter</a></li>
															<li><a href="<?php print $variables['url']; ?>projects/">Projects</a></li>
															<li><a href="//events.eclipse.org/">Events</a></li>
			                      </ul>
			                      <ul class="col-sm-6 list-unstyled">
			                        <li>
			                          <p><strong>Working Groups</strong></p>
			                        </li>
			                        <li><a href="http://wiki.eclipse.org/Auto_IWG">Automotive</a></li>
			                        <li><a href="http://locationtech.org">LocationTech</a></li>
			                        <li><a href="http://lts.eclipse.org">Long-Term Support</a></li>
			                        <li><a href="http://iot.eclipse.org">Internet of Things</a></li>
			                        <li><a href="http://polarsys.org">PolarSys</a></li>
			                      </ul>
			                      <ul class="col-sm-6 list-unstyled">
			                        <li>
			                          <p><strong>Explore</strong></p>
			                        </li>
			                        <li><a href="//marketplace.eclipse.org">Eclipse Marketplace</a></li>
															<li><a href="https://bugs.eclipse.org/bugs/">Bugzilla</a></li>
															<li><a href="<?php print $variables['url']; ?>forums/">Eclipse Forums</a></li>
															<li><a href="//www.planeteclipse.org/">Planet Eclipse</a></li>
															<li><a href="//wiki.eclipse.org/">Eclipse Wiki</a></li>
			                      </ul>
			                      <ul class="col-sm-6 list-unstyled">
			                        <li>
			                          <p><strong>Legal</strong></p>
			                        </li>
					    								<li><a href="<?php print $variables['url']; ?>legal/privacy.php">Privacy Policy</a></li>
				                    	<li><a href="<?php print $variables['url']; ?>legal/termsofuse.php">Terms of Use</a></li>
												    	<li><a href="<?php print $variables['url']; ?>legal/copyright.php">Copyright Agent</a></li>
				 									   	<li><a href="<?php print $variables['url']; ?>legal/">Legal</a></li>
					  								  <li><a href="<?php print $variables['url']; ?>org/foundation/contact.php">Contact Us</a></li>
			                      </ul>
			                    </div>
			                  </div>
			                </li>
			              </ul>
			            </li>
			          </ul>
			        </div>
			    </div>
		      <div class="action-links col-md-9 col-sm-6 hidden-xs">
		        <div id="button-container">
			        <!-- Split button -->
							<div class="btn-group hidden-sm">
							  <a href="https://dev.eclipse.org/site_login/createaccount.php" class="btn btn-info"><i class="fa fa-user fa-fw"></i> Sign in</a>
							  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li><a href="https://dev.eclipse.org/site_login/createaccount.php">Create account</a></li>
							    <li><a href="https://dev.eclipse.org/site_login/createaccount.php">Forgot my password</a></li>
							    <li class="divider"></li>
							    <li><a href="<?php print $variables['url']; ?>donate/">Friends of Eclipse</a></li>
							  </ul>
							</div>
						  <a href="<?php print $variables['url']; ?>downloads/" class="btn btn-warning">Download</a>
					  </div>
		      </div>
		    </div>
		  </div>
		</header>
		<?php if (!$variables['theme_variables']['hide_breadcrumbs']) :?>
			<section id="breadcrumb" class="<?php print $variables['theme_variables']['breadcrumbs_classes'];?>">
			  <div class="container">
					<ol class="breadcrumb">
			      <li><a href="<?php print $variables['url']; ?>">Home</a></li>
			      <li><a href="<?php print $variables['url']; ?>org">About us</a></li>
			      <li class="active"><?php print $variables['page']['title'];?></li>
			    </ol>
			    <?php print $variables['theme_variables']['breadcrumbs_html'];?>
		    </div>
	    </section>
    <?php endif; ?>
		<main role="main">
		  <div class="<?php print $variables['theme_variables']['main_container_classes'];?>">
        <?php print $variables['deprecated'];?>
