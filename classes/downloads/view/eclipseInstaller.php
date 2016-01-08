<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']) || empty($platforms)){exit();}
?>
<div class="installer">
  <div class="title">
    <div class="row">
      <div class="col-sm-6">
        <img class="installer-logo" width="150" src="/downloads/assets/public/images/logo-installer.png" />
      </div>
      <div class="col-sm-18">
        <h2>Try the Eclipse <span class="orange">Installer</span> <span class="label label-default label-new">NEW</span></h2>
        <p>The easiest way to install and update your Eclipse Development Environment.</p>
        <p class="padding-top-5">
          <a class="btn btn-warning btn-sm" data-target="#collapseEinstaller" class="solstice-collapse orange" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseEinstaller">
          Find out more <i class="fa fa-chevron-down"></i>
          </a>
        </p>
      </div>
    </div>
  </div><!-- end of title -->
  <div class="options">
    <div class="clearfix">
      <div class="col-sm-6 download-count-eclipse-installer">
       <?php if (!empty($download_count)) :?>
         <p><?php print number_format($download_count);?><br/>
           Downloads
         </p>
      <?php endif;?>
      </div>

     <?php foreach ($platforms as $platform):?>
       <div class="col-sm-6">
         <div class="padding-bottom-5"></div>
          <p><?php print $platform['icon'];?> <?php print $platform['label'];?></p>
          <ul class="list-inline">
            <li><i class="fa fa-download white"></i></li>
            <?php print implode('', $platform['links']);?>
          </ul>
        </div>
      <?php endforeach;?>
    </div>
  </div><!-- end of .options -->
</div> <!-- end of .installer -->

<div id="collapseEinstaller1">
  <div class="collapse<?php if (isset($_GET['show_instructions'])) { print ' in';}?>" id="collapseEinstaller">
    <div class="well">
      <?php include('eclipseInstaller_instructions.php');?>
    </div>
  </div>
</div>