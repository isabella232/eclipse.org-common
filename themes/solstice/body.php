<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
$nav = $this->getThemeFile('nav');

?>
<?php print $this->getBreadcrumbHtml();?>
<main<?php print $this->getAttributes('main');?>>
  <div<?php print $this->getAttributes('main-container');?>>
    <div class="row">
      <div<?php print $this->getAttributes('main-container-content');?>>
        <?php print $this->getDeprecatedMessage();?>
        <?php print $this->getHeaderNav();?>
        <?php print $this->getSystemMessages();?>
        <?php print $this->getThemeVariables('main_container_html');?>
        <?php print $this->getHtml();?>
      </div>
      <?php print $nav;?>
    </div>
  </div>
</main> <!-- /#main-content-container-row -->