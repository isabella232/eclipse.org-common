<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * //www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

?>

		<header role="banner">
		  <div class="container">
			  <div class="row" id="row-logo-search">
				  <div id="logo" class="col-sm-8">
	          <?php print $variables['promotion'];?>
				  </div>
				  <div id="search" class="col-md-4">
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
			    <div class="navbar yamm  col-md-7">
		        <div class="navbar-header">
		          <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
		        </div>
		        <div id="navbar-collapse-1" class="navbar-collapse collapse">
		          <ul class="nav navbar-nav">
		            <!-- Classic list -->
		            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">List<b class="caret"></b></a>
		              <ul class="dropdown-menu">
		                <li>
		                  <!-- Content container to add padding -->
		                  <div class="yamm-content">
		                    <div class="row">
		                      <ul class="col-sm-2 list-unstyled">
		                        <li>
		                          <p><strong>Section Title</strong></p>
		                        </li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                      </ul>
		                      <ul class="col-sm-2 list-unstyled">
		                        <li>
		                          <p><strong>Links Title</strong></p>
		                        </li>
		                        <li><a href="#"> Link Item </a></li>
		                        <li><a href="#"> Link Item </a></li>
		                        <li><a href="#"> Link Item </a></li>
		                        <li><a href="#"> Link Item </a></li>
		                        <li><a href="#"> Link Item </a></li>
		                        <li><a href="#"> Link Item </a></li>
		                      </ul>
		                      <ul class="col-sm-2 list-unstyled">
		                        <li>
		                          <p><strong>Section Title</strong></p>
		                        </li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                      </ul>
		                      <ul class="col-sm-2 list-unstyled">
		                        <li>
		                          <p><strong>Section Title</strong></p>
		                        </li>
		                        <li>List Item</li>
		                        <li>List Item</li>
		                        <li>
		                          <ul>
		                            <li><a href="#"> Link Item </a></li>
		                            <li><a href="#"> Link Item </a></li>
		                            <li><a href="#"> Link Item </a></li>
		                          </ul>
		                        </li>
		                      </ul>
		                    </div>
		                  </div>
		                </li>
		              </ul>
		            </li>
		            <!-- Accordion demo -->
		            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Accordion<b class="caret"></b></a>
		              <ul class="dropdown-menu">
		                <li>
		                  <div class="yamm-content">
		                    <div class="row">
		                      <div id="accordion" class="panel-group">
		                        <div class="panel panel-default">
		                          <div class="panel-heading">
		                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a></h4>
		                          </div>
		                          <div id="collapseOne" class="panel-collapse collapse in">
		                            <div class="panel-body">Ut consectetur ullamcorper purus a rutrum. Etiam dui nisi, hendrerit feugiat scelerisque et, cursus eu magna. </div>
		                          </div>
		                        </div>
		                        <div class="panel panel-default">
		                          <div class="panel-heading">
		                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a></h4>
		                          </div>
		                          <div id="collapseTwo" class="panel-collapse collapse">
		                            <div class="panel-body">Nullam pretium fermentum sapien ut convallis. Suspendisse vehicula, magna non tristique tincidunt, massa nisi luctus tellus, vel laoreet sem lectus ut nibh. </div>
		                          </div>
		                        </div>
		                        <div class="panel panel-default">
		                          <div class="panel-heading">
		                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a></h4>
		                          </div>
		                          <div id="collapseThree" class="panel-collapse collapse">
		                            <div class="panel-body">Praesent leo quam, faucibus at facilisis id, rhoncus sit amet metus. Sed vitae ipsum non nibh mattis congue eget id augue. </div>
		                          </div>
		                        </div>
		                      </div>
		                    </div>
		                  </div>
		                </li>
		              </ul>
		            </li>
		            <!-- Classic dropdown -->
		            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Classic<b class="caret"></b></a>
		              <ul role="menu" class="dropdown-menu">
		                <li><a tabindex="-1" href="#"> Action </a></li>
		                <li><a tabindex="-1" href="#"> Another action </a></li>
		                <li><a tabindex="-1" href="#"> Something else here </a></li>
		                <li class="divider"></li>
		                <li><a tabindex="-1" href="#"> Separated link </a></li>
		              </ul>
		            </li>
		            <!-- Pictures -->
		            <li class="dropdown yamm-fw"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Pictures<b class="caret"></b></a>
		              <ul class="dropdown-menu">
		                <li>
		                  <div class="yamm-content">
		                    <div class="row">
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="http://placekitten.com/150/190/"></a></div>
		                    </div>
		                  </div>
		                </li>
		              </ul>
		            </li>
		          </ul>
		        </div>
			    </div>
		      <div class="action-links col-md-5">
		        <div id="button-container">
			        <!-- Split button -->
							<div class="btn-group">
							  <button type="button" class="btn btn-info">Sign in</button>
							  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li><a href="https://dev.eclipse.org/site_login/createaccount.php">Create account</a></li>
							    <li><a href="https://dev.eclipse.org/site_login/createaccount.php">Forgot my password</a></li>
							    <li class="divider"></li>
							    <li><a href="//eclipse.org/donate/">Friends of Eclipse</a></li>
							  </ul>
							</div>
						  <a href="#" class="btn btn-warning">Download</a>
					  </div>
		      </div>
		    </div>
		  </div>
		</header>

		<main role="main">
		  <div class="container">
        <?php print $variables['deprecated'];?>
