<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */


require_once ('lib/eclipseussblob.class.php');

/**
 * Member class
 *
 * Usage example:
 *
 * include_once('member.class.php');
 * $Member = new Member();
 * $Member->loginSSO();
 *
 * @author chrisguindon
 */
class Member extends EclipseUSSBlob {

  /**
   * Class constructor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
  }

  /**
   * Fetch all members
   *
   * @param array params
   *
   * return array
   */
  public function indexMember($params = array()) {
    $url = 'foundation/member';
    if (!empty($params)) {
      $query = http_build_query($params);
      $url .= "?" . $query;
    }

    $response = $this->get($url);
    return $response;
  }

  /**
   * Retrieve a member based on the organization id
   *
   * @param string $organization_id
   *
   * @return array
   */
  public function retrieveMember($organization_id = "") {
    if (!is_numeric($organization_id)) {
      return array();
    }
    $response = $this->get('foundation/member/' . $organization_id);
    $this->unsetHeader('If-Match');
    return $response;
  }
}