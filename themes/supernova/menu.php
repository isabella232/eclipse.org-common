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
			  <div class="row">
				  <div id="logo" class="col-sm-8">
	          <?php print $variables['promotion'];?>
				  </div>
				  <div id="search" class="col-md-4">
					  <form action="//www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s" role="form" class="form-inline">
						  <fieldset class="form-group">
							  <input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
							  <input id="search-box" type="text" name="q" size="25" class="form-control"/>
							  <input id="search-button" type="submit" name="sa" value="Search" class="btn btn-default"/>
						  </fieldset>
					  </form>
					  <script type="text/javascript" src="//www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>
				  </div>
	      </div>
	      <div class="row">
		      <nav role="navigation" class="col-md-7">
		        <ul class="list-inline">
		          <?php print $variables['menu']['main_menu'];?>
		        </ul>
		      </nav>
		      <div class="action-links col-md-5">
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
		</header>

		<main role="main">
		  <div class="container">
        <?php print $variables['deprecated'];?>
