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
 *******************************************************************************/
?>

<?php if (is_a($this, 'Cla') && $this->Friend->getUID()): ?>
  <br>
  <div class="alert alert-success" role="alert">
    <strong>Congradulations!</strong> You've signed a ECA.
  </div>
  <div class="alert alert-info" role="alert">
    <p>The Eclipse Contributor Agreement that we have on record for
    you will expire on <?php print $this->getClaExpiryDate(); ?></p>
  </div>
  <p>If you've changed employers or your contact information,
  please invalidate your current ECA and complete the form again.
  <strong>Note that if you invalidate / renew your ECA, it cannot be undone;
  you will be prompted to sign a new ECA.</strong></p>
  <form action="#open_tab_cla" method="POST">
    <input type="hidden" name="state" value="invalidate_cla">
    <input type="hidden" name="form_name" value="cla-form">
    <button class="btn btn-primary">Invalidate / Renew ECA</button>
  </form>
<?php endif; ?>