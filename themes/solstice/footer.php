<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
$base_url_link = $Theme->getBaseUrl();
?>
      </div>
    </main> <!-- /#main-content-container-row -->
    <p id="back-to-top">
      <a class="visible-xs" href="#top">Back to the top</a>
    </p>
    <footer role="contentinfo" id="solstice-footer">

      <div class="container">

        <div class="row">
          <section id="footer-eclipse-foundation" class="col-sm-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">
            <h2 class="section-title">Eclipse Foundation</h2>
            <ul class="nav">
              <li><a href="<?php print $base_url_link; ?>org/">About us</a></li>
              <li><a href="<?php print $base_url_link; ?>org/foundation/contact.php">Contact Us</a></li>
              <li><a href="<?php print $base_url_link; ?>donate">Donate</a></li>
              <li><a href="<?php print $base_url_link; ?>org/documents/">Governance</a></li>
              <li><a href="<?php print $base_url_link; ?>artwork/">Logo and Artwork</a></li>
              <li><a href="<?php print $base_url_link; ?>org/foundation/directors.php">Board of Directors</a></li>
            </ul>
          </section>
          <section id="footer-legal" class="col-sm-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print ">
            <h2 class="section-title">Legal</h2>
            <ul class="nav">
              <li><a href="<?php print $base_url_link; ?>legal/privacy.php">Privacy Policy</a></li>
              <li><a href="<?php print $base_url_link; ?>legal/termsofuse.php">Terms of Use</a></li>
              <li><a href="<?php print $base_url_link; ?>legal/copyright.php">Copyright Agent</a></li>
              <li><a href="<?php print $base_url_link; ?>org/documents/epl-v10.php">Eclipse Public License </a></li>
              <li><a href="<?php print $base_url_link; ?>legal/">Legal Resources </a></li>

            </ul>
          </section>

          <section id="footer-useful-links" class="col-sm-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">
            <h2 class="section-title">Useful Links</h2>
            <ul class="nav">
              <li><a href="https://bugs.eclipse.org/bugs/">Report a Bug</a></li>
              <li><a href="//help.eclipse.org/">Documentation</a></li>
              <li><a href="<?php print $base_url_link; ?>contribute/">How to Contribute</a></li>
              <li><a href="<?php print $base_url_link; ?>mail/">Mailing Lists</a></li>
              <li><a href="<?php print $base_url_link; ?>forums/">Forums</a></li>
              <li><a href="//marketplace.eclipse.org">Marketplace</a></li>
            </ul>
          </section>

          <section id="footer-other" class="col-sm-offset-1 col-xs-11 col-sm-7 col-md-6 col-md-offset-0 hidden-print">

            <h2 class="section-title">Other</h2>
            <ul class="nav">
               <li><a href="<?php print $base_url_link; ?>ide/">IDE and Tools</a></li>
              <li><a href="<?php print $base_url_link; ?>projects">Community of Projects</a></li>
              <li><a href="<?php print $base_url_link; ?>org/workinggroups/">Working Groups</a></li>
            </ul>

            <ul class="list-inline social-media">
              <li><a href="https://twitter.com/EclipseFdn"><i class="fa fa-twitter-square"></i></a></li>
              <li><a href="https://plus.google.com/+Eclipse"><i class="fa fa-google-plus-square"></i></a></li>
              <li><a href="https://www.facebook.com/eclipse.org"><i class="fa fa-facebook-square"></i> </a></li>
              <li><a href="https://www.youtube.com/user/EclipseFdn"><i class="fa fa-youtube-square"></i></a></li>
            </ul>

          </section>
          <div id="copyright"  class="col-sm-offset-1 col-sm-14 col-md-24 col-md-offset-0">
            <div>
              <span class="hidden-print"><?php print $Theme->getLogo('white')?></span>
              <p id="copyright-text"><?php print $Theme->getCopyrightNotice();?></p>
            </div>
          </div>
        <a href="#" class="scrollup">Back to the top</a>
        </div>
      </div>
    </footer>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php print $Theme->getThemeUrl('solstice')?>public/javascript/main.min.js"></script>
    <?php print $Theme->getExtraJsFooter();?>
    <?php print $google_javascript;?>
  </body>
</html>
