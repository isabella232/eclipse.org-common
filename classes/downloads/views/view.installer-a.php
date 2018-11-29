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

<div class="text-center">
  <span class="downloads-logo vertical-align"><img height="50" alt="Eclipse" src="/downloads/assets/public/images/logo-eclipse.png"></span>
  <h3>Get <strong>Eclipse IDE <?php print $release_title; ?></strong></h3>
  <p>Install your favorite desktop IDE packages.</p>
  <p>
    <?php foreach ($installer_links['links'] as $link): ?>
      <a class="<?php print $link['link_classes']; ?>" href="<?php print $link['url']; ?>" title="<?php print $link['text']; ?> Download"><?php print $link['text_prefix'] . ' ' . $link['text']; ?></a>
    <?php endforeach; ?>
  </p>
  <p><a href="/downloads/packages" class="grey-link">Download Packages</a> | <a class="grey-link" href="/downloads/packages/installer" title="Instructions">Need Help?</a></p>
</div>