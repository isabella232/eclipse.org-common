<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>
<div class="promoted-plugin">
  <div class="row">
    <div class="col-md-24">
      <div class="header">
        <div class="row">
          <div class="col-xs-6"><i class="fa fa-star">&nbsp;</i></div>
          <div class="col-xs-18">
            <h3>Promoted Plugin</h3>
          </div>
        </div>
      </div>
      <div class="content">
        <div class="row">
          <div class="col-xs-8"><img src="<?php print $this->ad->getImage();?>"></div>
          <div class="col-xs-16">
            <p><strong><?php print $this->ad->getTitle();?></strong></p>
            <p><?php print $this->ad->getBody();?></p>
          </div>
        </div>
      </div>
      <div class="download clearfix drag_installbutton">
        <a href="<?php print $this->ad->getUrl();?>" class="drag">
          <i class="fa fa-download pull-left"></i>
           <span class="padding-top-10" style="display:block">INSTALL NOW</span>
          <span class="tooltip show-right"><span class="h3">Drag to Install!</span><br/>Drag to your running Eclipse workspace.</span>
        </a>
      </div>
    </div>
  </div>
</div>