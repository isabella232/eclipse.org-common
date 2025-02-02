<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - initial API and implementation
 *   Christopher Guindon (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>

<span class="downloads-logo vertical-align"><img height="50" alt="Promoted Downloads" src="<?php print $this->ad->getImage();?>"></span>
<!--<h3 class="downloads-items-header">Deploy IBM Bluemix</h3>-->
<p><?php print $this->ad->getBody();?></p>
<p class="orange small"><i class="fa fa-star" aria-hidden="true"></i> Promoted Download</p>
<p class="visible-xs visible-sm"><a href="//eclipse.org/go/<?php print $this->ad->getCampaign() . '?impression_id=' . $impression_id;?>" class="btn btn-warning btn-xs">Get it</a></p>
<p class="visible-xs visible-sm downloads-items-hover-box-links"><a href="//eclipse.org/go/<?php print $this->ad->getCampaign() . '?impression_id=' . $impression_id;?>">Learn More</a></p>
<div class="downloads-items-hover-box">
  <h4 class="downloads-items-header"><?php print $this->ad->getTitle();?></h4>
  <p class="downloads-items-hover-box-text"><?php print $this->ad->getBody();?></p>
  <p><a href="//eclipse.org/go/<?php print $this->ad->getCampaign() . '?impression_id=' . $impression_id;?>" class="btn btn-warning btn-xs"><i class="fa fa-star" aria-hidden="true"></i> Promoted Download</a></p>
  <p class="downloads-items-hover-box-links"><a href="//eclipse.org/go/<?php print $this->ad->getCampaign() . '?impression_id=' . $impression_id;?>">Learn More</a></p>
</div>