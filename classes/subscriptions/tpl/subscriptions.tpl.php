<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
if(!is_a($this, 'Mailchimp')){
  exit();
}
?>
  <table class="table">
    <thead>
      <tr>
        <th>Newsletters</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Eclipse Newsletter</td>
        <td>
          <?php if ($this->getIsSubscribed()): ?>
            <button id="subscription-form-submit" class="btn btn-danger btn-xs float-right">Unsubscribe</button>
          <?php else: ?>
            <button id="subscription-form-submit" class="btn btn-primary btn-xs float-right">Subscribe</button>
          <?php endif;?>
        </td>
      </tr>
    </tbody>
  </table>