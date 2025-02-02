<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once (dirname(__FILE__) . "/../membership/membershipImage.class.php");
require_once ("campaignImpression.class.php");

/**
 *
 * Display paid promotions on Eclipse web properties
 *
 * @author chrisguindon
 */
class Promotions {

  /**
   * Eclipse Advertisements
   *
   * 1. Paid ads should split 40% of the total impressions
   * 2. EclipseCon should take 10% of the total impressions
   * 3. Members ads should split the remaning 50%.
   *
   * @return array $retVal
   */
  static public function getPromos($filter = array()) {
    $promos = array();

    /**
     * PAID ads (30%)
     */
    $promos[] = array(
      'url' => 'PAID_FROGLOGIC_BANNER_1',
      'imageurl' => '/membership/promo/images/banner_1_froglogic.png',
      'memberName' => 'FrogLogic',
      'type' => 'paid',
      'weight' => 4
    );

    $promos[] = array(
      'url' => 'PAID_FROGLOGIC_BANNER_2',
      'imageurl' => '/membership/promo/images/banner_2_froglogic_proven_java_learn_more.png',
      'memberName' => 'FrogLogic',
      'type' => 'paid',
      'weight' => 4
    );

    $promos[] = array(
      'url' => 'PROMO_KONDUIT',
      'imageurl' => '/membership/promo/images/konduit.jpg',
      'memberName' => 'Konduit',
      'type' => 'strat_ad',
      'weight' => 5
    );

    // Strategic Member Ads
    $promos[] = array(
      'url' => 'PROMO_ORACLE',
      'imageurl' => '/membership/promo/images/oepe_ad_200x200.jpg',
      'memberName' => 'Oracle',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'IBM_JAZZ',
      'imageurl' => '/membership/promo/images/ibm200x200-ibm_cloud.jpg',
      'memberName' => 'IBM',
      'type' => 'strat_ad',
      'weight' => 6
    );
    $promos[] = array(
      'url' => 'PROMO_SAP',
      'imageurl' => '/membership/promo/images/sap200x200.jpg',
      'memberName' => 'SAP',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_OBEO',
      'imageurl' => '/membership/promo/images/Sirius_ad_200.png',
      'memberName' => 'Obeo',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_CEA',
      'imageurl' => '/membership/promo/images/PapyrusCEA.gif',
      'memberName' => 'CEA',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_FUJITSU',
      'imageurl' => '/membership/promo/images/fujitsu_200px.jpg',
      'memberName' => 'Fujitsu',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_RED_HAT',
      'imageurl' => '/membership/promo/images/redhat-ad.jpg',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_FOKUS',
      'imageurl' => '/membership/promo/images/fokus_promo.jpg',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_HUAWEI',
      'imageurl' => '/membership/promo/images/huawei-ad-eclipse-hdc.jpg',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 5
    );

    $promos[] = array(
      'url' => 'PROMO_EUROPEAN_RESEARCH_CASE_STU',
      'imageurl' => '/membership/promo/images/european-research-innovation-case-studies.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_ITEMIS_CASE_STUDY',
      'imageurl' => '/membership/promo/images/itemis-open-source-case-study.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_ENABLING_TRANSFORMATION',
      'imageurl' => '/membership/promo/images/enabling_digital_transformation.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_ENABLING_TRANSFORMATION_JS',
      'imageurl' => '/membership/promo/images/enabling_digital_transformation.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 5
    );

    $promos[] = array(
      'url' => 'PROMO_JAKARTA_EE_DEV_SURVEY_2020',
      'imageurl' => '/membership/promo/images/jakarta_ee_developer_survey_2020.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 6
    );

    $promos[] = array(
      'url' => 'PROMO_JAKARTA_EE_YOUTUBE',
      'imageurl' => '/membership/promo/images/jakarta_ee_youtube_ad.png',
      'memberName' => 'EclipseFoundation',
      'type' => 'strat_ad',
      'weight' => 5
    );

    if (!empty($filter)) {
      $filter_promo = array();
      foreach ($promos as $ad) {
        if (in_array($ad['type'], $filter)) {
          $filter_promo[] = $ad;
        }
      }
      return $filter_promo;
    }

    return $promos;
  }

  /**
   * Return promo HTML
   *
   * @return string
   */
  static public function output($filter = array()) {
    $promos = self::getPromos($filter);
    $ad = (isset($_GET['ad_id']) && is_numeric($_GET['ad_id']) && !empty($promos[$_GET['ad_id']])) ? $promos[$_GET['ad_id']] : self::_array_rand_weighted($promos);
    if (empty($ad['type'])) {
      return "";
    }

    if ($ad['type'] == 'strategic') {
      return self::_buildStrategicMemberAd($ad);
    }
    return self::_buildAd($ad);
  }

  /**
   * Build Strategic Member ad
   *
   * @param unknown $array
   * @return string
   */
  static private function _buildStrategicMemberAd($array) {
    $mimg = new MemberImage();
    list($width, $height) = $mimg->getsmall_image($array['memberID']);

    // check for errors ( -1 in both means something bad happened getting the
    // image details)
    if ($width >= 1 && $height >= 1) {
      $heightText = 'height="' . $height . '" ';
      return '<div class="eclipsefnd-ad ad-strategic ad-strategic-frontpage"><a href="/membership/showMember.php?member_id=' . $array['memberID'] . '" rel="nofollow" style="background-image: url(\'/membership/scripts/get_image.php?size=small&id=' . $array['memberID'] . '\')">' . $array['memberName'] . '</a></div>';
    }
    return "";
  }

  /**
   * Build stadard promo ad
   * @param unknown $array
   * @return string
   */
  static private function _buildAd($array) {
    if (empty($array) || empty($array['url']) || empty($array['imageurl']) || empty($array['memberName'])) {
      return "";
    }

    if ($array['url'] === "PROMO_ENABLING_TRANSFORMATION_JS") {
      $return = '<div class="eclipsefnd-ad-js-plugin" data-publish-target="eclipse_org"></div>';
      return $return;
    }

    $CampaignImpression = new CampaignImpression($array['url']);
    $impression_id = $CampaignImpression->recordImpression();
    return '<div class="eclipsefnd-ad ad-strategic ad-strategic-default"><a href="/go/' . $array['url'] . '?impression_id=' . $impression_id . '" rel="nofollow" style="background-image: url(\'' . $array['imageurl'] . '\')">' . $array['memberName'] . '</a></div>';
  }

  /**
   * Select an add based of #weight value
   *
   * @param unknown $values
   *
   * @return unknown
   */
  static private function _array_rand_weighted($values) {
    if (empty($values)) {
      return array();
    }
    $totalWeight = 0;
    foreach ($values as $rr) {
      $totalWeight += $rr['weight'];
    }
    $r = mt_rand(1, $totalWeight);
    foreach ($values as $item) {
      if ($r <= $item['weight'])
        return $item;
      $r -= $item['weight'];
    }
  }

}
