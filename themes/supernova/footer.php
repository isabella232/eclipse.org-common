<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>
			</div> <!-- /#bootnovacontent -->
		</div> <!-- /container -->

		<div class="container">
			<footer class="clearfix row">
				<div class="col-md-7">
					<ul id="footernav" class="list-inline">
						<li><a href="//eclipse.org/">Home</a></li>
						<li><a href="//eclipse.org/legal/privacy.php">Privacy Policy</a></li>
						<li><a href="//eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
						<li><a href="//eclipse.org/legal/copyright.php">Copyright Agent</a></li>
						<li><a href="//eclipse.org/legal/">Legal</a></li>
						<li><a href="//eclipse.org/org/foundation/contact.php">Contact Us</a></li>
					</ul>
				</div>
				<div class="col-md-5">
		    	<p id="copyright"><?php print $variables['footer']['copyright'];?></p>
		    </div>
			</footer>
		</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php print $variables['theme_url'];?>components/bootstrap/js/main.js"></script>
  </body>
</html>
