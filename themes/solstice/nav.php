<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *    gbarbier mia-software com - bug 284239
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation of solstice
 *******************************************************************************/

/**
 * Copyright (c) 2016, 2017, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Denis Roy (Eclipse Foundation)- initial API and implementation
 *   gbarbier mia-software com - bug 284239
 *   Christopher Guindon (Eclipse Foundation) - Initial implementation of solstice
 *   Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

$navigation = $this->getNav();
if (!empty($navigation['#items'])) :
?>
  <!-- nav -->
  <aside<?php print $this->getAttributes('main-sidebar');?>>
    <?php print $this->getThemeVariables('leftnav_html');?>

    <ul class="ul-left-nav fa-ul hidden-print" id="leftnav" role="tablist" aria-multiselectable="true">
      <?php foreach ($navigation['#items'] as $key => $link) :?>
        <?php if ($link['item']->getURL() == "") :?>
          <?php if ($link['item']->getTarget() == "__SEPARATOR") : ?>
            <li class="separator">
              <a class="separator">
                <?php print $link['item']->getText() ?>
              </a>
            </li>
          <?php else: ?>
            <li>
              <i class="fa fa-caret-right fa-fw"></i>
              <a class="nolink" href="#"><?php print $link['item']->getText() ?></a>
            </li>
          <?php endif; ?>
        <?php else: // if $link->getURL() is not empty. ?>
          <?php if($link['item']->getTarget() == "__SEPARATOR") :?>
            <li class="separator">
              <a class="separator" href="<?php print $link['item']->getURL() ?>">
                <?php print $link['item']->getText() ?>
              </a>
            </li>
          <?php else:?>
            <li role="tab" id="heading<?php print $key; ?>">
              <i class="fa fa-caret-right fa-fw"></i>
              <a role="button" data-toggle="collapse" data-parent="#leftnav" href="#leftnav-children<?php print $key; ?>" aria-expanded="true" aria-controls="leftnav-children<?php print $key; ?>">
                <?php print $link['item']->getText() ?>
              </a>
            </li>
            <div id="leftnav-children<?php print $key; ?>" class="panel-collapse collapse <?php print !empty($link['classes']) ? $link['classes'] : ""; ?>"tabpanel" aria-labelledby="headin<?php print $key; ?>">
              <?php foreach ($navigation['#items'][$key]['children'] as $child) :?>
                <li class="main-sidebar-children">
                  <i class="fa fa-caret-right fa-fw"></i>
                  <a href="<?php print $child->getURL() ?>" target="<?php print ($child->getTarget() == "_blank") ? "_blank" : "_self" ?>">
                    <?php print $child->getText() ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        <?php endif;?>
      <?php endforeach; ?>
    </ul>
    <?php if (!empty( $navigation['html_block'])) :?>
      <div<?php print $this->getAttributes('main-sidebar-html-block');?>>
        <?php print $navigation['html_block']; ?>
      </div>
    <?php endif;?>
  </aside>
  <?php print $navigation['html_block_suffix']; ?>
<?php endif;?>