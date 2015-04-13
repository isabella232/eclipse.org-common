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
<p id="back-to-top">
  <a class="visible-xs" href="#top">Back to the top</a>
</p>
<footer id="solstice-footer" role="contentinfo">
  <div class="container no-border">
    <div class="row">
      <section class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print" id="footer-eclipse-foundation">
        <div class="region region-footer-1">
          <section class="block block-menu clearfix" id="block-menu-menu-about">
            <h2 class="block-title">PolarSys</h2>
            <div class="block-content">
              <ul class="menu nav">
                <li class="first leaf"><a href="https://www.polarsys.org/about-us">About us</a></li>
                <li class="leaf"><a href="https://www.polarsys.org/contact-us">Contact us</a></li>
                <li class="leaf"><a href="https://www.polarsys.org/governance">Governance</a></li>
                <li class="leaf"><a href="https://www.polarsys.org/members%20">Members</a></li>
                <li class="last leaf"><a href="https://www.polarsys.org/polarsys-logo"> Logo</a></li>
              </ul>
            </div>
          </section>
          <!-- /.block -->
        </div>
      </section>
      <section class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print" id="footer-legal">
        <h2 class="section-title">Legal</h2>
        <ul class="nav">
          <li class="link_privacy first"><a href="//www.eclipse.org/legal/privacy.php">Privacy Policy</a></li>
          <li class="link_terms"><a href="//www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
          <li class="link_copyright"><a href="//www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
          <li class="link_epl"><a href="//www.eclipse.org/org/documents/epl-v10.php">Eclipse Public License</a></li>
          <li class="link_legal last"><a href="//www.eclipse.org/legal/">Legal Resources</a></li>
        </ul>
      </section>
      <section class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print" id="footer-useful-links">
        <div class="region region-footer-3">
          <section class="block block-menu clearfix" id="block-menu-menu-usefull-links">
            <h2 class="block-title">Useful Links</h2>
            <div class="block-content">
              <ul class="menu nav">
                <li class="first leaf"><a href="https://www.polarsys.org/blog">Blog</a></li>
                <li class="leaf"><a href="https://www.polarsys.org/community">Mailing list</a></li>
                <li class="leaf"><a href="https://www.polarsys.org/news">News and Events</a></li>
                <li class="last leaf"><a href="https://www.polarsys.org/polarsys-newsletter">Newsletter</a></li>
              </ul>
            </div>
          </section>
          <!-- /.block -->
        </div>
      </section>
      <section class="col-xs-24 col-md-11 footer-other-working-groups col-md-offset-1 hidden-print" id="footer-other">
        <div class="col-sm-10 col-xs-offset-1 col-md-11 col-md-offset-1 footer-working-group-col" id="footer-working-group-left">
          <a title="Home" href="/"><img alt="Home" src="<?php print $variables['theme_url'];?>public/images/logo/polarsys.png" class="logo-eclipse-default img-responsive"></a><br><img alt="PolarSys sectors" src="<?php print $variables['theme_url'];?>public/images/template/polarsys/header-bg-icons.png" class="img-responsive">
          <h2 class="section-title sr-only">Other</h2>
          <ul class="list-inline social-media">
            <li class="link_twitter first"><a href="//twitter.com/EclipseFdn"><i class="fa fa-twitter-square"></i></a></li>
            <li class="link_google"><a href="//plus.google.com/+Eclipse"><i class="fa fa-google-plus-square"></i></a></li>
            <li class="link_facebook"><a href="//www.facebook.com/eclipse.org"><i class="fa fa-facebook-square"></i></a></li>
            <li class="link_youtube last"><a href="//www.youtube.com/user/EclipseFdn"><i class="fa fa-youtube-square"></i></a></li>
          </ul>
        </div>
        <div class="col-sm-10 col-xs-offset-1 col-sm-offset-3 col-md-11 col-md-offset-1 footer-working-group-col" id="footer-working-group-right">
          <a title="Eclipse Foundation" href="https://www.eclipse.org/"><img alt="Eclipse Foundation homepage" src="<?php print $variables['theme_url'];?>public/images/logo/eclipse-800x188.png" class="logo-eclipse-default img-responsive"></a>
          <p class="padding-top-15">PolarSys is a Working Group of The Eclipse Foundation.</p>
          <p>Copyright &copy; 2015 The Eclipse Foundation. All Rights Reserved.</p>
        </div>
      </section>
      <a class="scrollup" href="#" style="display: none;">Back to the top</a>
    </div>
  </div>
</footer>
</div>

<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php print $variables['theme_url'];?>public/javascript/main.min.js"></script>
<?php print $variables['page']['extra_js_footer'];?>
<?php print $google_javascript;?>
</body>
</html>
