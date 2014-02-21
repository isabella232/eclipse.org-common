<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

?>
	  <div id="main-navbar" class="navbar navbar-inverse navbar-fixed-top navbar-default hidden-sm hidden-md hidden-lg">
	    <div class="container">
	      <div class="navbar-header">
	        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </button>
	        <a class="navbar-brand" href="#"><?php //print $variables['logo'];?>Eclipse Foundation</a>
	      </div>
	      <div class="collapse navbar-collapse">
	        <ul class="nav navbar-nav">
	          <?php print $variables['menu']['main_menu'];?>
	          <li class="dropdown">
	    		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Visit other Eclipse Sites<span class="caret"></span></a>
	    			  <ul class="dropdown-menu">
	    			    <li><a href="http://marketplace.eclipse.org">Eclipse Marketplace</a></li>
							  <li><a href="http://live.eclipse.org">Eclipse Live</a></li>
							  <li><a href="https://bugs.eclipse.org/bugs/">Bugzilla</a></li>
							  <li><a href="http://www.eclipse.org/forums/">Eclipse Forums</a></li>
							  <li><a href="http://www.planeteclipse.org/">Planet Eclipse</a></li>
							  <li><a href="http://wiki.eclipse.org/">Eclipse Wiki</a></li>
							  <li><a href="http://portal.eclipse.org">My Foundation Portal</a></li>
	    			  </ul>
	  			  </li>
	        </ul>
	      </div><!--/.nav-collapse -->
		  </div>
	  </div>

	  <div class="container">
	  	<div class="row hidden-xs">
				<div id="logo" class="col-sm-4">
	        <?php print $variables['promotion'];?>
				</div>

				<div class="col-sm-8 hidden-xs" id="other-eclipse">
					<ul id="other-eclipse-sites" class="list-inline pull-right">
						<li><a href="http://marketplace.eclipse.org"><img alt="Eclipse Marketplace" src="//dev.eclipse.org/custom_icons/marketplace.png"/>&nbsp;<span>Eclipse Marketplace</span></a></li>
						<li><a href="http://live.eclipse.org"><img alt="Eclipse Live" src="//dev.eclipse.org/custom_icons/audio-input-microphone-bw.png"/>&nbsp;<span>Eclipse Live</span></a></li>
						<li><a href="https://bugs.eclipse.org/bugs/"><img alt="Bugzilla" src="//dev.eclipse.org/custom_icons/system-search-bw.png"/>&nbsp;<span>Bugzilla</span></a></li>
						<li><a href="http://www.eclipse.org/forums/"><img alt="Forums" src="//dev.eclipse.org/large_icons/apps/internet-group-chat.png"/>&nbsp;<span>Eclipse Forums</span></a></li>
						<li><a href="http://www.planeteclipse.org/"><img alt="Planet Eclipse" src="//dev.eclipse.org/large_icons/devices/audio-card.png"/>&nbsp;<span>Planet Eclipse</span></a></li>
						<li><a href="http://wiki.eclipse.org/"><img alt="Eclipse Wiki" src="//dev.eclipse.org/custom_icons/accessories-text-editor-bw.png"/>&nbsp;<span>Eclipse Wiki</span></a></li>
						<li><a href="http://portal.eclipse.org"><img alt="MyFoundation Portal" src="//dev.eclipse.org/custom_icons/preferences-system-network-proxy-bw.png"/><span>My Foundation Portal</span></a></li>
					</ul>
				</div>
	    </div>

			<div id="header" class="row hidden-xs">
				<div id="menu" class="col-md-8"><ul class="list-inline"><?php print $variables['menu']['main_menu'];?></ul></div>
				<div id="search" class="col-md-4 hidden-sm">
					<form action="http://www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s" role="form" class="form-inline">
						<fieldset class="form-group">
							<input type="hidden" name="cx" value="017941334893793413703:sqfrdtd112s" />
							<input id="search-box" type="text" name="q" size="25" class="form-control"/>
							<input id="search-button" type="submit" name="sa" value="Search" class="btn btn-default"/>
						</fieldset>
					</form>
					<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=searchbox_017941334893793413703%3Asqfrdtd112s&amp;lang=en"></script>
				</div>
			</div>

			<div id="main-content-container-row" class="main-content-area row">
	      <?php print $variables['deprecated'];?>