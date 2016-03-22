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
?>

    </div>
  </main>
  <p id="back-to-top">
    <a class="visible-xs" href="#top">Back to the top</a>
  </p>
  <footer role="contentinfo" id="solstice-footer">
    <div class="container no-border">
      <div class="row">
        <section  id="footer-eclipse-foundation" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
          <div class="region region-footer-1 solstice-region-element-count-1 row">
            <section id="block-menu-menu-about" class="block block-menu block-region-footer-1 block-menu-about clearfix">
              <h2 class="block-title">PolarSys</h2>
              <div class="block-content">
                <ul class="menu nav">
                  <li class="first leaf"><a href="//polarsys.org/about-us" title="">About us</a></li>
                  <li class="leaf"><a href="//polarsys.org/contact-us" title="">Contact us</a></li>
                  <li class="leaf"><a href="//polarsys.org/governance" title="">Governance</a></li>
                  <li class="leaf"><a href="//polarsys.org/members%20" title="">Members</a></li>
                  <li class="last leaf"><a href="/polarsys-logo" title=""> Logo</a></li>
                </ul>
              </div>
            </section>
            <!-- /.block -->
          </div>
        </section>
        <section  id="footer-legal" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
          <h2 class="section-title">Legal</h2>
          <ul class="nav">
            <li class="link_privacy first"><a href="//www.eclipse.org/legal/privacy.php">Privacy Policy</a></li>
            <li class="link_terms"><a href="//www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
            <li class="link_copyright"><a href="//www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
            <li class="link_epl"><a href="//www.eclipse.org/org/documents/epl-v10.php">Eclipse Public License</a></li>
            <li class="link_legal last"><a href="//www.eclipse.org/legal/">Legal Resources</a></li>
          </ul>
        </section>
        <section  id="footer-useful-links" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
          <div class="region region-footer-3 solstice-region-element-count-1 row">
            <section id="block-menu-menu-usefull-links" class="block block-menu block-region-footer-3 block-menu-usefull-links clearfix">
              <h2 class="block-title">Useful Links</h2>
              <div class="block-content">
                <ul class="menu nav">
                  <li class="first leaf"><a href="//polarsys.org/projects" title="">Projects</a></li>
                  <li class="leaf"><a href="//polarsys.org//polarsys.org/og" title="">Blog</a></li>
                  <li class="leaf"><a href="//polarsys.org/faq" title="Frequently Asked Questions">FAQ</a></li>
                  <li class="leaf"><a href="//polarsys.org/news" title="">News and Events</a></li>
                  <li class="last leaf"><a href="//polarsys.org/polarsys-newsletter" title="">Newsletter</a></li>
                </ul>
              </div>
            </section>
            <!-- /.block -->
          </div>
        </section>
        <section  id="footer-other" class="col-xs-24 col-md-11 footer-other-working-groups col-md-offset-1 hidden-print">
          <div id="footer-working-group-left" class="col-sm-10 col-xs-offset-1 col-md-11 col-md-offset-1 footer-working-group-col">
            <?php print $Theme->getLogo('default_responsive_link')?><br/>
            <?php print $Theme->getLogo('polarsys_sectors')?>

            <h2 class="section-title sr-only">Other</h2>
            <ul class="list-inline social-media">
              <li class="link_twitter first"><a href="//twitter.com/EclipseFdn"><i class="fa fa-twitter-square"></i></a></li>
              <li class="link_google"><a href="//plus.google.com/+Eclipse"><i class="fa fa-google-plus-square"></i></a></li>
              <li class="link_facebook"><a href="//www.facebook.com/eclipse.org"><i class="fa fa-facebook-square"></i></a></li>
              <li class="link_youtube last"><a href="//www.youtube.com/user/EclipseFdn"><i class="fa fa-youtube-square"></i></a></li>
            </ul>
          </div>
          <div  id="footer-working-group-right" class="col-sm-10 col-xs-offset-1 col-sm-offset-3 col-md-11 col-md-offset-1 footer-working-group-col">
            <?php print $Theme->getLogo('eclipse_footer_link');?>
            <p class="padding-top-15">PolarSys is a Working Group of The Eclipse Foundation.</p>
             <p><?php print $Theme->getCopyrightNotice();?></p>
          </div>
        </section>
        <a href="#" class="scrollup">Back to the top</a>
      </div>
    </div>
  </footer>
</div>


    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//www.eclipse.org<?php print $Theme->getThemeUrl('solstice')?>public/javascript/main.min.js"></script>
    <?php print $Theme->getExtraJsFooter();?>
  </body>
</html>
