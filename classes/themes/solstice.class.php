<?php
/**
 * *****************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

require_once ('baseTheme.class.php');
class Solstice extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('solstice');
    parent::__construct($App);

    $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    $this->setAttributes('main-menu-wrapper', 'col-sm-14 col-md-16 col-lg-19 reset');
    $this->setAttributes('header-right', 'col-sm-10 col-md-8 col-lg-5 hidden-print hidden-xs pull-right');

    $this->setAttributes('footer1', 'col-sm-6 hidden-print');
    $this->setAttributes('footer2', 'col-sm-6 hidden-print');
    $this->setAttributes('footer3', 'col-sm-6 hidden-print');
    $this->setAttributes('footer4', 'col-sm-6 hidden-print');

    // Footer links
    $this->setFooterLinks("about_us", "About Us", $this->getBaseUrl() . "org/", "region_1", 1);
    $this->setFooterLinks("contact_us", "Contact Us", $this->getBaseUrl() . "org/foundation/contact.php", "region_1", 2);
    $this->setFooterLinks("donate", "Donate", $this->getBaseUrl() . "donate", "region_1", 3);
    $this->setFooterLinks("governance", "Governance", $this->getBaseUrl() . "org/documents/", "region_1", 4);
    $this->setFooterLinks("logo_and_artwork", "Logo and Artwork", $this->getBaseUrl() . "artwork/", "region_1", 5);
    $this->setFooterLinks("board_of_directors", "Board of Directors", $this->getBaseUrl() . "org/foundation/directors.php", "region_1", 6);

    $this->setFooterLinks("privary_policy", "Privacy Policy", $this->getBaseUrl() . "legal/privacy.php", "region_2", 1);
    $this->setFooterLinks("terms_of_use", "Terms of Use", $this->getBaseUrl() . "legal/termsofuse.php", "region_2", 2);
    $this->setFooterLinks("copyright_agent", "Copyright Agent", $this->getBaseUrl() . "legal/copyright.php", "region_2", 3);
    $this->setFooterLinks("epl", "Eclipse Public License", $this->getBaseUrl() . "legal/epl-2.0/", "region_2", 4);
    $this->setFooterLinks("legal_resources", "Legal Resources", $this->getBaseUrl() . "legal/", "region_2", 5);

    $this->setFooterLinks("report_a_bug", "Report a Bug", "https://bugs.eclipse.org/bugs/", "region_3", 1);
    $this->setFooterLinks("documentation", "Documentation", "//help.eclipse.org/", "region_3", 2);
    $this->setFooterLinks("contribute", "How to Contribute", $this->getBaseUrl() . "contribute/", "region_3", 3);
    $this->setFooterLinks("mailing_lists", "Mailing Lists", $this->getBaseUrl() . "mail/", "region_3", 4);
    $this->setFooterLinks("forums", "Forums", $this->getBaseUrl() . "forums/", "region_3", 5);
    $this->setFooterLinks("marketplace", "Marketplace", "//marketplace.eclipse.org", "region_3", 6);

    $this->setFooterLinks("ide_and_tools", "IDE and Tools", $this->getBaseUrl() . "ide/", "region_4", 1);
    $this->setFooterLinks("projects", "Community of Projects", $this->getBaseUrl() . "projects", "region_4", 2);
    $this->setFooterLinks("working_groups", "Working Groups", $this->getBaseUrl() . "org/workinggroups/", "region_4", 3);
    $this->setFooterLinks("research", "Research@Eclipse", $this->getBaseUrl() . "org/research/", "region_4", 4);
    $this->setFooterLinks("security", "Report a Vulnerability", $this->getBaseUrl() . "security/", "region_4", 5);
    $this->setFooterLinks("service_status", "Service Status", "https://status.eclipse.org", "region_4", 6);
  }

  /**
   * Get Html of Footer Region 1
   */
  public function getFooterRegion1() {
    return <<<EOHTML
    <h2 class="section-title">Eclipse Foundation</h2>
    {$this->getFooterLinks('region_1')}
EOHTML;
  }

  /**
   * Get Html of Footer Region 2
   */
  public function getFooterRegion2() {
    return <<<EOHTML
    <h2 class="section-title">Legal</h2>
    {$this->getFooterLinks('region_2')}
EOHTML;
  }

  /**
   * Get Html of Footer Region 3
   */
  public function getFooterRegion3() {
    return <<<EOHTML
    <h2 class="section-title">Useful Links</h2>
    {$this->getFooterLinks('region_3')}
EOHTML;
  }

  /**
   * Get Html of Footer Region 4
   */
  public function getFooterRegion4() {
    return <<<EOHTML
    <h2 class="section-title">Other</h2>
    {$this->getFooterLinks('region_4')}
EOHTML;
  }

  /**
   * Get Html of Footer Region 5
   */
  public function getFooterRegion5() {
    return <<<EOHTML
      <div class="col-sm-24 margin-top-20">
        <div class="row">
          <div id="copyright" class="col-md-16">
            <p id="copyright-text">{$this->getCopyrightNotice()}</p>
          </div>
          <div class="col-md-8 social-media">
            <ul class="list-inline">
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://twitter.com/EclipseFdn">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-twitter fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.facebook.com/eclipse.org">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-facebook fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.youtube.com/user/EclipseFdn">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-youtube fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.linkedin.com/company/eclipse-foundation">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-linkedin fa-stack-1x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
EOHTML;
  }
}
