<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");

class Membership {
  private $App;

  private $members = array();

  private $id = NULL;

  private $profile = NULL;

  function __construct(){
    global $App;
    $this->App = $App;
    $this->members = array(
      'strategic' => array(
        'content_class' => 'tab-pane active',
        'level' => 'strategic',
        'list_class' => 'active',
        'members' => array(),
        'img' => '/membership/images/type/strategic-members.png',
        'title' => 'Strategic Members',
      ),
      'enterprise' => array(
        'level' => 'enterprise',
        'content_class' => 'tab-pane',
        'list_class' => '',
        'members' => array(),
        'img' => '/membership/images/type/enterprise-members.png',
        'title' => 'Enterprise Members',
      ),
      'solutions' => array(
        'content_class' => 'tab-pane',
        'level' => 'solutions',
        'list_class' => '',
        'members' => array(),
        'img' => '/membership/images/type/solutions-members.png',
        'title' => 'Solutions Members',
      ),
      'associate' => array(
        'content_class' => 'tab-pane',
        'level' => 'associate',
        'list_class' => '',
        'members' => array(),
        'img' => '/membership/images/type/associate-members.png',
        'title' => 'Associate Members',
      ),
    );
  }

  /**
   * Set member id
   *
   * @param string $id
   * @return boolean
   */
  function setId($id = NULL) {
    $options = array(
      'options' => array('min_range' => 0)
    );

    if (filter_var($id, FILTER_VALIDATE_INT, $options) !== FALSE) {
      $this->id = $id;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Fetch membership profile(s).
   *
   * @return Ambigous <multitype:, multitype:string , boolean, multitype:unknown , multitype:multitype:string NULL  >|boolean|multitype:
   */
  function fetchProfile() {
    $sql = "SELECT
      ORG.member_type,
      ORGI.OrganizationID as id,
      ORG.name1 as name,
      ORGI.short_description as body,
      ORGI.long_description as full_text,
      ORG.member_type as type,
      ORGI.company_url IS NULL AS COMPLETE,
      ORGI.small_logo as small_logo,
      ORGI.large_logo as large_logo
    FROM OrganizationInformation as ORGI
    RIGHT JOIN organizations as ORG on ORGI.OrganizationID = ORG.organization_id
    WHERE ORG.member_type in ('SD', 'SC', 'AP', 'AS', 'ENTRP')";

    if (!is_null($this->id)) {
      $sql .= " and ORGI.OrganizationID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->id));
    }
    $sql .= "ORDER BY ORG.name1";

    $rs = $this->App->eclipse_sql($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      $row = $this->_mb_convert_encoding($row);
      $row['body'] = stripcslashes($row['body']);
      $row['full_text'] = stripcslashes($row['full_text']);
      $row['title_link'] = "";
      $row['small_logo_link'] = "";
      $row['large_logo_link'] = "";

      if (!empty($row['id'])) {
        $row['small_logo_link'] = $row['large_logo_link'] = $row['title_link'] .= '<a href="/membership/showMember.php?member_id=' . $row['id'] .'" title="' . $row['name'] . '">';
      }

      $row['title_link'] .= $row['name'];
      $small_logo_src = '/membership/images/eclipse-mp-member-144x69.png';
      if (!empty($row['small_logo'])) {
        $small_logo_src = 'data:image/jpeg;base64,' . base64_encode($row['small_logo']);
      }

      $large_logo_src = '/membership/images/eclipse-mp-member-144x69.png';
      if (!empty($row['large_logo'])) {
        $large_logo_src = 'data:image/jpeg;base64,' . base64_encode($row['large_logo']);
      }

      $row['small_logo_link'] .= '<img src="' . $small_logo_src . '"  title="' . $row['name'] . '" class="img-responsive"/>';
      $row['large_logo_link'] .= '<img src="' . $large_logo_src . '"  title="' . $row['name'] . '" class="img-responsive"/>';

      if (!empty($row['id'])) {
        $row['title_link'] .= '</a>';
        $row['small_logo_link'] .= '</a>';
        $row['large_logo_link'] .= '</a>';
      }

      switch($row['member_type']) {
        case 'AP':
          $this->members['solutions']['members'][] = $row;
          break;
        case 'AS':
          $this->members['associate']['members'][] = $row;
          break;
        case 'ENTRP':
          $this->members['enterprise']['members'][] = $row;
          break;
        case 'SD' || 'SC':
          $this->members['strategic']['members'][] = $row;
          break;
      }
    }

    if (!is_null($this->id)) {
      foreach($this->members as $level){
        if (!empty($level['members'])) {
          $member = $level['members'][0];
          unset($level['members']);
          $this->profile = array_merge($level, $member);
          $this->profile['mp_listings'] = $this->fetchMarketplaceListings();
          $this->profile['mp_training'] = $this->fetchMarketplaceTrainingListings();
          $this->profile['products'] = $this->fetchMemberProducts();
          $this->profile['projects'] = $this->fetchMemberProjects();
          return $this->profile;
        }
      }
      return FALSE;
    }
    return $this->members;
  }

  /**
   * Fetch Marketplace listing
   *
   * @return boolean|multitype:unknown
   */
  function fetchMarketplaceListings() {
    if (is_null($this->profile)) {
      return FALSE;
    }

    $sql = "
    SELECT
      N.title,
      CTR.field_companyname_value as name,
      N.nid,
      if(V.field_version_value IS NULL ,'',V.field_version_value) as version,
      B.body_value as teaser
    FROM field_data_field_companyname as CTR
    INNER JOIN node as N on CTR.entity_id = N.nid
    LEFT JOIN field_data_field_version as V on V.entity_id = N.nid and V.revision_id = N.vid
    INNER JOIN field_data_body as B on B.entity_id = N.nid and B.revision_id = N.vid
    WHERE N.status = 1 and CTR.field_companyname_value = ";
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($this->profile['name']));
    $result = $this->App->marketplace_sql($sql);

    $return = array();
    while ($row = mysql_fetch_assoc($result)) {
      $row = $this->_mb_convert_encoding($row);
      $row['teaser'] = $this->_ellipsis($row['teaser']);
      $return[] = $row;
    }

    return $return;
  }

  /**
   * Fetch Marketplace Training Listings
   *
   * @return multitype:|multitype:unknown
   */
  function fetchMarketplaceTrainingListings(){
    $return = array();
    if (is_null($this->profile)) {
      return $return;
    }

    $sql = "
    SELECT
      N.title,
      N.nid,
      B.body_value as teaser,
      TD.field_trainingdesc_value as training,
      CTT.field_consultingdesc_value as consulting
    FROM node as N
    INNER JOIN field_data_body as B on B.entity_id = N.nid and B.revision_id = N.vid
    INNER JOIN field_data_field_trainingdesc as TD on TD.entity_id = N.nid and TD.revision_id = N.vid
    INNER JOIN field_data_field_consultingdesc as CTT on CTT.entity_id = N.nid and CTT.revision_id = N.vid
    WHERE N.status = 1 AND N.type = 'training' AND N.title =";
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($this->profile['name']));
    $result = $this->App->marketplace_sql($sql);

    while ($row = mysql_fetch_assoc($result)) {
      $row = $this->_mb_convert_encoding($row);
      $row['teaser'] = $this->_ellipsis($row['teaser']);
      $row['training'] = $this->_ellipsis($row['training']);
      $row['consulting'] = $this->_ellipsis($row['consulting']);
      $return[] = $row;
    }

    return $return;

  }

  /**
   * Fetch Member products
   *
   * @return multitype:|multitype:unknown
   */
  function fetchMemberProducts() {
    $return = array();
    if (is_null($this->profile)) {
      return $return;
    }

    $sql = "SELECT ProductID as id, name as name, description as teaser, product_url as url
      FROM OrganizationProducts
      WHERE OrganizationID = ";
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($this->profile['id']));
    $sql .= " ORDER by ProductID";

    $result = $this->App->eclipse_sql($sql);

    while ($row = mysql_fetch_assoc($result)) {
      $row = $this->_mb_convert_encoding($row);
      $return[] = $row;
    }

    return $return;
  }

  /**
   * Fetch Member projects
   * @return multitype:|multitype:multitype:string NULL
   */
  function fetchMemberProjects() {

    $sql = "select distinct project from ProjectCompanyActivity where orgId=";
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($this->profile['id']));
    $result = $this->App->dashboard_sql($sql);


    $data = array();
    while ($row = mysql_fetch_assoc($result)) {
      if (preg_match('/^locationtech\.(.*)$/', $row['project'])) {
        $data['locationtech']['url'] = 'https://www.locationtech.org';
      }
      elseif (preg_match('/^polarsys\.(.*)$/',$row['project'])) {
        $data['polarsys']['url'] = 'https://www.polarsys.org';
      }
      else {
        $data['eclipse']['url'] = 'https://projects.eclipse.org';
      }
    }

    $return = array();
    if (!empty($data)) {
      foreach($data as &$forge) {
        if ($json = file_get_contents($forge['url'] . '/json/member/' . $this->profile['id'])){
         $forge = array_merge(json_decode($json, TRUE), $forge);
        }
        else{
          continue;
        }
        if (!empty($forge['projects'])) {
          foreach ($forge['projects'] as $project_id => $project){
            $return[] = array(
              'id' => $project_id,
              'url' => $forge['url'] . '/projects/' . $project_id,
              'name' => $project['name'],
            );
          }
        }
      }
    }

    return $return;
  }

  /**
   * Fix db encoding problems
   *
   * @param unknown $row
   * @return unknown
   */
  private function _mb_convert_encoding($row){
    foreach($row as $key => &$r) {
      if ($key != 'large_logo' && $key != 'small_logo') {
        $r = mb_convert_encoding($r, 'Windows-1252', 'UTF-8');
      }
    }
    return $row;
  }

  /**
   * Add elipsis to a string
   *
   * @param string $string
   * @return string
   */
  private function _ellipsis($string = "") {
    return substr(strip_tags(html_entity_decode($string)), 0, 250) . "...";
  }

}
