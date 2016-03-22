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
<footer role="contentinfo" id="solstice-footer">
  <div class="container no-border">
    <div class="row">
      <section  id="footer-eclipse-foundation" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
        <div class="region region-footer-1 solstice-region-element-count-1 row">
          <section id="block-menu-menu-locationtech" class="block block-menu block-region-footer-1 block-menu-locationtech clearfix">
            <h2 class="block-title">LocationTech</h2>
            <div class="block-content">
              <ul class="menu nav">
                <li class="first leaf"><a href="<?php print $Theme->getBaseUrl();?>/about" title="">About Us</a></li>
                <li class="leaf"><a href="<?php print $Theme->getBaseUrl();?>/contact" title="">Contact us</a></li>
                <li class="leaf"><a href="<?php print $Theme->getBaseUrl();?>/charter" title="">Governance</a></li>
                <li class="leaf"><a href="<?php print $Theme->getBaseUrl();?>/steeringcommittee" title="">Steering Committee</a></li>
                <li class="last leaf"><a href="<?php print $Theme->getBaseUrl();?>/jobs" title="">Jobs</a></li>
              </ul>
            </div>
          </section>
          <!-- /.block -->
        </div>
      </section>
      <section  id="footer-legal" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
        <div class="region region-footer-2 solstice-region-element-count-1 row">
          <section id="block-menu-menu-locationtech-legal" class="block block-menu block-region-footer-2 block-menu-locationtech-legal clearfix">
            <h2 class="block-title">Legal</h2>
            <div class="block-content">
              <ul class="menu nav">
                <li class="first leaf"><a href="https://www.eclipse.org/legal/copyright.php" title="">Copyright Agent</a></li>
                <li class="leaf"><a href="https://www.eclipse.org/legal/privacy.php" title="">Privacy Policy</a></li>
                <li class="leaf"><a href="https://www.eclipse.org/legal/termsofuse.php" title="">Terms of Use</a></li>
                <li class="last leaf"><a href="https://www.eclipse.org/legal/" title="">Legal Resources</a></li>
              </ul>
            </div>
          </section>
          <!-- /.block -->
        </div>
      </section>
      <section  id="footer-useful-links" class="col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print">
        <div class="region region-footer-3 solstice-region-element-count-1 row">
          <section id="block-menu-menu-locationtech-useful-links" class="block block-menu block-region-footer-3 block-menu-locationtech-useful-links clearfix">
            <h2 class="block-title">Useful Links</h2>
            <div class="block-content">
              <ul class="menu nav">
                <li class="first leaf"><a href="https://locationtech.org/mailman/listinfo" title="">Discussion lists</a></li>
                <li class="leaf"><a href="https://github.com/LocationTech" title="">Github</a></li>
                <li class="leaf"><a href="https://locationtech.org/wiki" title="">Wiki</a></li>
                <li class="leaf"><a href="http://foss4g-na.org" title="">FOSS4G NA</a></li>
                <li class="leaf"><a href="http://tour.locationtech.org" title="">Tour</a></li>
                <li class="last leaf"><a href="http://fedgeoday.org" title="">FedGeoDay</a></li>
              </ul>
            </div>
          </section>
          <!-- /.block -->
        </div>
      </section>
      <section  id="footer-other" class="col-xs-24 col-md-11 footer-other-working-groups col-md-offset-1 hidden-print">
        <div id="footer-working-group-left" class="col-sm-10 col-xs-offset-1 col-md-11 col-md-offset-1 footer-working-group-col">
          <?php print $Theme->getLogo('default_responsive_link');?><br/>
          <h2 class="section-title sr-only">Other</h2>
          <ul class="list-inline social-media">
            <li class="link_twitter first"><a href="//twitter.com/locationtech"><i class="fa fa-twitter-square"></i></a></li>
            <li class="link_facebook last"><a href="//www.facebook.com/groups/401867609865450/"><i class="fa fa-facebook-square"></i></a></li>
          </ul>
        </div>
        <div  id="footer-working-group-right" class="col-sm-10 col-xs-offset-1 col-sm-offset-3 col-md-11 col-md-offset-1 footer-working-group-col">
         <span class="hidden-print"><?php print $Theme->getLogo('white_link')?></span>
          <p class="padding-top-15">LocationTech is a Working Group of The Eclipse Foundation.</p>
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
