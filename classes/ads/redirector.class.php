<?php
/**
 * Copyright (c) 2015, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");

/**
 * Redirector for Eclipse campaign manager
 *
 * Based off the work from Denis Roy (/go/redirector.php)
 *
 * @author chrisguindon
 */
class Redirector {

  /**
   * Campaign Tag
   *
   * @var string
   */
  protected $tag = "";

  /**
   * Campaign SubTag
   *
   * @var string
   */
  protected $subtag = "";

  /**
   * $impression_click_id from CampaignImpressions
   *
   * @var numeric
   */
  protected $impression_click_id = "";

  /**
   * Redirect action
   *
   * Browser will be redirected,
   * otherwise an error page is shown
   */
  function redirect() {
    if ($result = $this->getCampaign()) {

      // redirect the browser now
      $sub_tag = $this->getSubTag();
      if (!empty($sub_tag)) {
        header("Location: " . $result['url'] . "?" . $sub_tag);
      }
      else {
        header("Location: " . $result['url']);
      }

      // Record click
      $this->_insertCampaignClicks();
      // Exit otherwise the server will try to
      // send out the error page
      exit();
    }
    $this->_showErrorPage();
  }

  /**
   * Get Campaign based off $tag
   *
   * @return boolean|mysql_query()
   */
  public function getCampaign() {
    $App = new App();
    $tag = $this->getTag();
    if (empty($tag)) {
      return FALSE;
    }
    $sql = "SELECT TargetUrl as url FROM Campaigns WHERE CampaignKey = " . $App->returnQuotedString($App->sqlSanitize($tag)) . " AND DateExpires > CURDATE()";
    $result = $App->eclipse_sql($sql);
    if ($row = mysql_fetch_assoc($result)) {
      return $row;
    }
    return FALSE;
  }


  /**
   * Get impressionID
   *
   * @return numeric
   */
  public function getImpressionId() {
    return $this->impression_click_id;
  }

  /**
   * Set ImpressionID
   *
   * @param numeric $impression_click_id
   *
   * @return boolean
   */
  public function setImpressionId($impression_click_id = "") {
    if (!empty($impression_click_id)) {
      $this->impression_click_id = $impression_click_id;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set tags and sub tags
   *
   * @param string $tag
   *
   * @return boolean
   */
  public function setTags($tag = "") {
    if (empty($tag) || !is_string($tag)) {
      return FALSE;
    }
    $tag = strtoupper($tag);
    $x = stripos($tag, '@');
    if ($x !== FALSE) {
      $subtag = substr($tag, $x + 1);
      // strip potentially bad characters from file
      $this->subtag = preg_replace('/["\'?%$#@!;*&]/i', '', $subtag);
      $tag = substr($tag, 0, $x);
    }

    // strip potentially bad characters from file
    $this->tag = preg_replace('/["\'?%$#@!;*&]/i', '', $tag);
    return TRUE;
  }

  /**
   * Get tag
   *
   * @return string
   */
  public function getTag() {
    return $this->tag;
  }

  /**
   * Get subtag
   *
   * @return string
   */
  public function getSubTag() {
    return $this->subtag;
  }

  /**
   * Insert Campaign Clicks
   *
   * @return mysql_query()
   */
  protected function _insertCampaignClicks() {
    $App = new App();
    $tag = $this->getTag();

    if (empty($tag)) {
      return FALSE;
    }

    if (rand(0, 1000) < 1) {
      // 1 of every 1,000 hits (0.1%) will clean up
      $deleteSql = "DELETE LOW_PRIORITY FROM CampaignClicks WHERE TimeClicked < DATE_SUB(NOW(), INTERVAL 1 YEAR)";
      $App->eclipse_sql($deleteSql);
    }

    $ip =  $App->anonymizeIP($App->getRemoteIPAddress());
    $ip = (!empty($ip)) ? $App->returnQuotedString($App->sqlSanitize($ip)) : 'NULL';

    $subtag = $this->getSubTag();
    $subtag = (!empty($subtag)) ? $App->returnQuotedString($App->sqlSanitize($subtag)) : 'NULL';

   // Make sure we have a valid impression Id
    $this->_validateImpressionId();
    $impression_click_id = $this->getImpressionId();
    $impression_click_id = (!empty($impression_click_id)) ? $App->returnQuotedString($App->sqlSanitize($impression_click_id)) : 'NULL';

    $sql = "INSERT /* /redirector.class.php */ INTO CampaignClicks (
            CampaignKey,
            SubKey,
            HostName,
            ImpressionClickID,
            TimeClicked
            ) VALUES (
            " . $App->returnQuotedString($App->sqlSanitize($tag)) . ",
            " . $subtag . ",
            " . $ip . ",
            " . $impression_click_id . ",
            NOW())";

    return $App->eclipse_sql($sql);
  }

  /**
   * Serve Error/Expired link page
   */
  protected function _showErrorPage() {
    $html = '<div id="maincontent">
        <div id="midcolumn">
          <h1>Expired Link</h1>
      <p> There was an error processing this link.  It is likely you are following an old link to an expired campaign activity.  For example,
      you may have clicked on a link to a survey that is now closed or to a special offer that is no longer applicable.  If you believe
      this link should work, please contact the source of the link.  While you are here, please browse some of our website by
      clicking on the appropriate tab at the top of the page.
      </p>

      <hr class="clearer" />
      </div>
    </div>';
    $App = new App();
    $Theme = $App->getThemeClass();
    $Theme->setHtml($html);
    $Theme->generatePage();
    exit();
  }

  /**
   * Validate ImpressionId
   *
   * @return boolean
   */
  protected function _validateImpressionId() {
    $impression_click_id = $this->getImpressionId();
    if (empty($impression_click_id)) {
      $this->impression_click_id = NULL;
      return FALSE;
    }
    $App = new App();
    $sql = "SELECT ImpressionClickID FROM CampaignImpressions WHERE ImpressionClickID = " . $App->returnQuotedString($App->sqlSanitize($impression_click_id));
    $result = $App->eclipse_sql($sql);
    if ($row = mysql_fetch_assoc($result)) {
      return TRUE;
    }
    $this->impression_click_id = NULL;
    return FALSE;
  }

}