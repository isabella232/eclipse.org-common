<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation)  - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>
<div class="eclipse-installer content">
  <div class="row">
    <div class="col-md-16">
      <h2>Try the Eclipse <strong>Installer</strong></h2>
      <p>The easiest way to install and update your Eclipse Development Environment.</p>
      <ul class="list-inline margin-bottom-0">
        <?php if ($this->getAllowToggle()) :?>
        <li>
          <a data-target="#collapseEinstaller" class="solstice-collapse orange" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseEinstaller">
          Find out more
          </a>
        </li>
        <?php endif;?>
        <li class="visible-md visible-lg">
          <?php if (!empty($download_count)) :?>
            <strong><i class="fa fa-download"></i> <?php print number_format($download_count);?> Downloads</strong>
          <?php endif;?>
        </li>
      </ul>
    </div>
    <div class="col-md-8 eclipse-installer-download-col">
      <p class="margin-top-10"><strong>Download</strong></p>
      <ul class="list-unstyled eclipse-installer-download-links">
        <?php foreach ($installer_links as $platform => $links):?>
          <?php
            print '<li>' . $links['label'] . ' ';
            $bar = FALSE;
            foreach ($links['links'] as $link) {
              print ($bar) ? ' | ' : '';
              print '<a href="' . $link['url'] . '" title="' . $link['text'] . ' Download" class="">' . $link['text'] . '</a>';
              $bar = TRUE;
            }
            print '</li>';
          ?>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
<?php print $this->getInstallerInstructions();?>
