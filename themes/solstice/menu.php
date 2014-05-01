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
			  <div class="row" id="row-logo-search">
				  <div id="header-left" class="col-sm-16 col-md-18">
	          <div class="hidden-xs">
	            <?php print $variables['promotion'];?>
	          </div>
					   <div id="main-menu" class="navbar yamm row">
			        <div id="navbar-collapse-1" class="navbar-collapse collapse">
			          <ul class="nav navbar-nav">
			            <?php print $variables['menu']['main_menu']; ?>
			            <?php print $variables['menu']['mobile_more'];?>
			            <!-- More -->
			            <li class="dropdown hidden-xs"><a href="#" data-toggle="dropdown" class="dropdown-toggle">More<b class="caret"></b></a>
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
					      <?php print $variables['logo_mobile']; ?>
	    				</div>
				    </div>
				  </div>
				  <div id="header-right" class=" col-sm-8 col-md-6 hidden-print hidden-xs">

				  	<a  href="https://www.surveymonkey.com/s/eclipsedesign" class="btn btn-danger btn-sm hidden-xs" target="_blank">
	            Submit feedback
	          </a>
				    <!-- Split button -->
						<div class="btn-group">
						  <a href="https://dev.eclipse.org/site_login/createaccount.php" class="btn btn-info btn-sm "><i class="fa fa-user fa-fw"></i> Sign in</a>
						  <button type="button" class="btn btn-info btn-sm  dropdown-toggle" data-toggle="dropdown">
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

					  <form action="//www.google.com/cse" id="form-eclipse-search" role="form" class="form-inline">
						  <fieldset class="form-group">
							  <input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
							  <input id="search-box" type="text" name="q" size="25" class="form-control"/>
							  <input id="search-button" type="submit" name="sa" value="Search" class="btn btn-default"/>
						  </fieldset>
					  </form>
					  <script type="text/javascript" src="//www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>

				    <div class="action-links hidden-xs">
              <a href="<?php print $variables['url']; ?>downloads/" class="btn btn-huge btn-warning"><i class="fa fa-download"></i> Download</a>
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
		<main role="main">
		  <div class="<?php print $variables['theme_variables']['main_container_classes'];?>" id="novaContent">
	        <?php print $variables['deprecated'];?>
