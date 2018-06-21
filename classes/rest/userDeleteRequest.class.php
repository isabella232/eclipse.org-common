<?php
/**
 * Copyright (c) 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */


require_once ('lib/eclipseussblob.class.php');

/**
 * UserDeleteRequest class
 *
 * Usage example:
 *
 * include_once('userDeleteRequest.class.php');
 * $UserDeleteRequest = new UserDeleteRequest();
 *
 * @author chrisguindon
 */
class UserDeleteRequest extends EclipseUSSBlob {

  /**
   * Delete a UserDeleteRequest
   *
   * @param int $id
   *
   * @return Response
   */
  public function deleteUserDeleteRequest($id = "") {
    if (!empty($id) && is_numeric($id)) {
      return $this->delete('account/user_delete_request/' .  $id);
    }
    return array();
  }

  /**
   * Query UserDeleteRequest entities
   *
   * @param array $query
   *
   * @return Response
   */
  public function indexUserDeleteRequest($query = array()) {
    $query_string = "";
    if (!empty($query) || !is_array($query)) {
      $query_string = '?' . http_build_query($query);
    }

    $response = $this->get("account/user_delete_request/" . $query_string);
    return $response;
  }

  /**
   * Update an UserDeleteRequest entity
   *
   * @param int $id
   * @param string $status
   *
   * @return Response
   */
   public function updateDeleteRequest($id = "", $status = "") {
     if (!empty($id) && is_numeric($id) && is_numeric($status)) {
       return $this->put('account/user_delete_request/' . $id, json_encode(array('status' => $status)));
     }
     return array();
  }
}