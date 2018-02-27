<?php
/**
 * Copyright (c) 2010, 2014, 2015, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation) - Initial API + Implementation
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

/**
 * CampaignImpression
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class CampaignImpression {

  /**
   * The Eclipse campaign key
   * @var unknown
   */
  private $campaign_key = '';

  /**
   * Constructor
   *
   * @param string $_campaign_key
   * @param string $_source (deprecated)
   */
  function __construct($_campaign_key, $_source = NULL) {
    $this->campaign_key = $_campaign_key;
  }

  /**
   * Record an impression in the Eclipse database
   */
  function recordImpression() {
    $App = new App();

    // We dont register ad impressions in devmode
    if ($App->devmode == TRUE) {
      return FALSE;
    }

    if (rand(0, 1000) < 1) {
      // 1 of every 1,000 hits (0.1%) will clean up
      $deleteSql = "DELETE LOW_PRIORITY FROM CampaignImpressions WHERE TimeImpressed < DATE_SUB(NOW(), INTERVAL 1 YEAR)";
      $App->eclipse_sql($deleteSql);
    }

    $url = parse_url($_SERVER['SCRIPT_URI']);
    $host = (!empty($url['host'])) ? $App->returnQuotedString($App->sqlSanitize(preg_replace('#^www\.(.+\.)#i', '$1', $url['host']))) : 'NULL';
    $source = (!empty($url['path'])) ? $App->returnQuotedString($App->sqlSanitize($url['path'])) : 'NULL';

    $ImpressionClickID = $App->getAlphaCode(rand(32, 64));
    $ip =  $App->anonymizeIP($App->getRemoteIPAddress());
    $ip = (!empty($ip)) ? $App->returnQuotedString($App->sqlSanitize($ip)) : 'NULL';

    $sql = "INSERT DELAYED INTO CampaignImpressions
        (ImpressionClickID, CampaignKey, Source, HostName, TimeImpressed, Host)
        VALUES (
      " . $App->returnQuotedString($App->sqlSanitize($ImpressionClickID)) . ",
      " . $App->returnQuotedString($App->sqlSanitize($this->campaign_key)) . ",
      " . $source . ",
      " . $ip . ",
      now(),
      " . $host . ")";

    $result = $App->eclipse_sql($sql);
    return $ImpressionClickID;
  }
}