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

?>
<!-- RUNTIMES PLATFORM -->
<div id="<?php print strtolower(str_replace(" ", "-", $category['title'])); ?>" class="downloads-section">
  <div class="container">
    <h2><span class="downloads-title"><?php print $category['title']; ?></span></h2>
    <div class="row downloads-content-padding">

      <?php if ($key == 'tool_platforms') :?>
        <!-- Installer -->
        <div class="col-md-10th col-sm-24">
          <div class="downloads-installer">
            <?php print $this->Installer->output('x86_64'); ?>
          </div>
        </div>
      <?php endif;?>

      <?php print $this->getProjectsList($key); ?>

      <?php if ($key == 'tool_platforms') :?>
        <!-- PROMOTED DOWNLOAD -->
        <div class="col-md-5th col-sm-8 col-xs-16 col-xs-offset-4 col-sm-offset-0 downloads-items downloads-promoted">
          <?php print $this->PromotedDownloads->output('layout_a'); ?>
        </div>
      <?php endif;?>

    </div>
  </div>
</div>