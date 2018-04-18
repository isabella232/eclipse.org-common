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

<div class="downloads-bar-ad" style="<?php print $variables['banner_styles']?>">
  <div class="container">
    <div class="row">
      <div class="col-lg-20 col-md-18 downloads-bar-ad-white-shape">
        <p><?php print $variables['body']; ?></p>
      </div>
      <div class="col-lg-4 col-md-6 downloads-bar-ad-white-content">
        <a class="btn btn-primary" href="/go/<?php print $variables['button_url']; ?>"><?php print $variables['button_text']; ?></a>
      </div>
    </div>
  </div>
</div>