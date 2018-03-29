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

// Making sure the proper breadcrumb class is set before initializing the breadcrumb html
$body = $this->getHtml();
?>

<?php print $this->getBreadcrumbHtml();?>
<main<?php print $this->getAttributes('main');?>>
  <?php print $body?>
</main> <!-- /#main-content-container-row -->