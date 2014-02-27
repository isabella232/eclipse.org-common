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
		  </div>
		</main> <!-- /#main-content-container-row -->

		<footer role="contentinfo">
		  <div class="container">
        <div class="row">
          <section class="col-md-3">
            <h2 class="section-title">Eclipse Foundation</h2>
				    <ul class="nav nav-pills nav-stacked">
				      <li><a href="<?php print $variables['url']; ?>org/">About us</a></li>
				      <li><a href="<?php print $variables['url']; ?>org/foundation/contact.php">Contact Us</a></li>
				      <li><a href="<?php print $variables['url']; ?>org/documents/">Governance</a></li>
				      <li><a href="<?php print $variables['url']; ?>artwork/">Logo and Artwork</a></li>
              <li><a href="<?php print $variables['url']; ?>org/foundation/directors.php">Board of Directors</a></li>
				    </ul>
			    </section>
          <section class="col-md-3">
            <h2 class="section-title">Legal</h2>
				    <ul class="nav nav-pills nav-stacked">
					    <li><a href="<?php print $variables['url']; ?>legal/privacy.php">Privacy Policy</a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/termsofuse.php">Terms of Use</a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/copyright.php">Copyright Agent</a></li>
				    	<li><a href="<?php print $variables['url']; ?>org/documents/epl-v10.php">Eclipse Public License </a></li>
				    	<li><a href="<?php print $variables['url']; ?>legal/">Legal Resources </a></li>

				    </ul>
			    </section>

			    <section class="col-md-3">
            <h2 class="section-title">Useful Links</h2>
				    <ul class="nav nav-pills nav-stacked">
				      <li><a href="https://bugs.eclipse.org/bugs/">Report a Bug</a></li>
						  <li><a href="//help.eclipse.org/">Documentation</a></li>
					    <li><a href="<?php print $variables['url']; ?>contribute/">How to Contribute</a></li>
					    <li><a href="<?php print $variables['url']; ?>mail/">Mailing Lists</a></li>
					    <li><a href="<?php print $variables['url']; ?>forums/">Forums</a></li>
							<li><a href="//marketplace.eclipse.org">Marketplace</a></li>

				    </ul>
			    </section>
			    <section class="col-md-3">
			      <h2 class="section-title">Other</h2>
			      <ul class="nav nav-pills nav-stacked">
			       	<li><a href="<?php print $variables['url']; ?>ide/">IDE and Tools</a></li>
				    	<li><a href="//projects.eclipse.org/">Community of Projects</a></li>
					    <li><a href="<?php print $variables['url']; ?>org/workinggroups/">Working Groups</a></li>
					  </ul>

			      <?php print $variables['logo']['white']; ?>
			      <ul class="social-media list-inline">
			        <li class="social-media-icon social-media-twitter"><a href="https://twitter.com/EclipseFdn"><span class="sr-only">Twitter</span></a></li>
			        <li class="social-media-icon social-media-google"><a href="https://plus.google.com/+Eclipse"><span class="sr-only">Google+</span></a></li>
			        <li class="social-media-icon social-media-facebook"><a href="https://www.facebook.com/eclipse.org"><span class="sr-only">Facebook</span></a></li>
					    <li class="social-media-icon social-media-youtube"><a href="https://www.youtube.com/user/EclipseFdn"><span class="sr-only">Youtube</span></a></li>
				    </ul>
	    	    <p id="copyright"><?php print $variables['footer']['copyright'];?></p>
	        </section>
        </div>
      </div>
		</footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php print $variables['theme_url'];?>public/javascript/main.min.js"></script>
  </body>
</html>
