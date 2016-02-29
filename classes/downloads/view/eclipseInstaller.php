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
  <div class="row row-eq-height">
    <div class="col-md-16 col-sm-14 col-xs-12">
      <div class="content">
        <h2>Try the Eclipse <span class="orange">Installer</span></h2>
        <p>The easiest way to install and update your Eclipse Development Environment.</p>
        <ul class="list-inline">
          <li>
            <a data-target="#collapseEinstaller" class="solstice-collapse orange" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseEinstaller">
            Find out more
            </a>
          </li>
          <li>
            <?php if (!empty($download_count)) :?>
              <strong><i class="fa fa-download"></i> <?php print number_format($download_count);?> Downloads</strong>
            <?php endif;?>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 options">
      <ul class="list-unstyled">
        <li><i class="fa fa-download white"></i></li>
        <li class="title"><?php print $download_link['label']; ?></li>
        <li>
          <ul class="list-inline links">
            <?php foreach ($download_link['links'] as $link): ?>
              <?php print $link; ?>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>

<div id="collapseEinstaller1">
  <div class="collapse<?php if (isset($_GET['show_instructions'])) { print ' in';}?>" id="collapseEinstaller">
    <div class="well">
      <?php include('eclipseInstaller_instructions.php');?>
    </div>
  </div>
</div>