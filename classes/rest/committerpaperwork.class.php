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
require_once ('lib/eclipseussblob.class.php');

/**
 * CommitterPaperwork class
 *
 * Usage example:
 *
 * include_once('committerpaperwork.class.php');
 * $CommitterPaperwork = new CommitterPaperwork();
 * $CommitterPaperwork->loginSSO();
 *
 * @author chrisguindon
 */
class CommitterPaperwork extends EclipseUSSBlob {

  private $data = array();

  /**
   * Class constructor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
  }

  /**
   * Create committer_paperwork record (POST)
   *
   * @param string $username
   * @param array $data
   */
  public function createCommitterPaperwork($username = NULL, $data = array()) {
    // Make sure the user is logged in.
    if (!$this->loginSSO()) {
      return $this->_errorNotLoggedIn();
    }

    // Validate username.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    if (empty($username) || !is_string($username)) {
      return $this->_errorBadRequest('username');
    }

    // As of PHP 5.4.11, the numbers +0 and -0 validate as both integers
    // as well as floats (using FILTER_VALIDATE_FLOAT and FILTER_VALIDATE_INT).
    // Before PHP 5.4.11 they only validated as floats (using
    // FILTER_VALIDATE_FLOAT).
    $required_field = array(
      'project_url' => FILTER_VALIDATE_URL,
      'election_url' => FILTER_VALIDATE_URL,
      'forge' => FILTER_SANITIZE_STRING,
      'project_id' => FILTER_SANITIZE_STRING,
      'election_status' => FILTER_VALIDATE_FLOAT
    );

    foreach ($data as $field_name => $field_value) {
      // Unknown field.
      if (!isset($required_field[$field_name])) {
        return $this->_errorBadRequest($field_name, 'unknown');
      }
    }

    foreach ($required_field as $field_name => $validator) {
      if (!isset($data[$field_name])) {
        // Missing field.
        return $this->_errorBadRequest($field_name);
      }

      // Validate field.
      if (filter_var($data[$field_name], $validator) === FALSE) {
        return $this->_errorBadRequest($field_name, 'validation failed');
      }
    }

    return $this->post('committer_paperwork/' . $username, json_encode($data));
  }

  /**
   * Delete CommitterPaperwork (DELETE)
   *
   * @param string $username
   * @param unknown $id
   */
  public function deleteCommitterPaperwork($username = "", $id = "", $etag = "") {
    // Make sure the user is logged in.
    if (!$this->loginSSO()) {
      return $this->_errorNotLoggedIn();
    }

    // Validate username.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    if (empty($username) || !is_string($username)) {
      return $this->_errorBadRequest('username');
    }

    // Validate id.
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (empty($id) && !is_int($id)) {
      return $this->_errorBadRequest('id');
    }

    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag
      ));
    }

    $response = $this->delete('committer_paperwork/' . $username . '/' . $id);
    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Retrieve committer_paperwork (GET)
   *
   * @param string $username
   * @param string $id
   */
  public function retrieveCommitterPaperwork($username = "", $id = "", $etag = "") {
    // Make sure the user is logged in.
    if (!$this->loginSSO()) {
      return $this->_errorNotLoggedIn();
    }

    // Validate username.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    if (empty($username) || !is_string($username)) {
      return $this->_errorBadRequest('username');
    }

    // Validate id.

    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (empty($id) && !is_int($id)) {
      return $this->_errorBadRequest('id');
    }

    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag
      ));
    }

    $response = $this->get('committer_paperwork/' . $username . '/' . $id);
    if (isset($response->code) && $response->code == 200) {
      $data = json_decode($response->body);
      $this->data[$data->id] = $data;
    }

    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Update committer paperwork record
   * @param unknown $username
   * @param unknown $id
   * @param array $data
   * @param string $etag
   */
  public function updateCommitterPaperwork($username = NULL, $id = NULL, $data = array(), $etag = "") {
    if (!$this->loginSSO()) {
      return $this->_errorNotLoggedIn();
    }

    // Validate username.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    if (empty($username) || !is_string($username)) {
      return $this->_errorBadRequest('username');
    }
    // Validate id.
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (empty($id) && !is_int($id)) {
      return $this->_errorBadRequest('id');
    }

    // As of PHP 5.4.11, the numbers +0 and -0 validate as both integers
    // as well as floats (using FILTER_VALIDATE_FLOAT and FILTER_VALIDATE_INT).
    // Before PHP 5.4.11 they only validated as floats (using
    // FILTER_VALIDATE_FLOAT).
    $fields = array(
      'project_url' => FILTER_VALIDATE_URL,
      'election_url' => FILTER_VALIDATE_URL,
      'forge' => FILTER_SANITIZE_STRING,
      'project_id' => FILTER_SANITIZE_STRING,
      'election_status' => FILTER_VALIDATE_FLOAT,
      'committer_paperwork_url' => FILTER_VALIDATE_URL,
      'committer_paperwork_status' => FILTER_VALIDATE_FLOAT
    );

    foreach ($fields as $field_name => $field_value) {
      // Unknown field.
      if (!isset($fields[$field_name])) {
        return $this->_errorBadRequest($field_name, 'unknown');
      }
    }

    foreach ($fields as $field_name => $validator) {
      // Validate field.
      if (isset($data[$field_name]) && filter_var($data[$field_name], $validator) === FALSE) {
        return $this->_errorBadRequest($field_name, 'validation failed');
      }
    }

    if (empty($this->data[$id]->etag) && empty($etag)) {
      return $this->_errorConflict();
    }

    if (empty($etag)) {
      $etag = $this->data[$id]->etag;
    }

    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag
      ));
    }

    $response = $this->put('committer_paperwork/' . $username . '/' . $id, json_encode($data));
    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Fetch commiter Paperwork index
   *
   * @param unknown $username
   * @param array $params
   * @param number $page
   * @param number $pagesize
   */
  public function indexCommitterPaperwork($username = NULL, $params = array(), $page = 1, $pagesize = 20) {
    if (!$this->loginSSO()) {
      return $this->_errorNotLoggedIn();
    }

    $url = 'committer_paperwork';
    // Validate username.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    if (!empty($username) && !is_string($username)) {
      return $this->_errorBadRequest('username', 'invalid');
    }
    else {
      $url .= '/' . $username;
    }

    $allowed_params = array(
      'election_status' => FILTER_VALIDATE_INT,
      'committer_paperwork_status' => FILTER_VALIDATE_INT
    );

    foreach ($params as $param => $validator) {
      if (!isset($allowed_params[$param])) {
        return $this->_errorBadRequest($param, 'invalid parameter');
      }
    }

    foreach ($allowed_params as $param => $validator) {
      if (!isset($params[$param])) {
        continue;
      }
      // Validate field.
      if (filter_var($params[$param], $validator) === FALSE) {
        return $this->_errorBadRequest($param, 'parameter validation failed');
      }
    }

    if (!is_int($page)) {
      return $this->_errorBadRequest('page', 'invalid');
    }

    if (!is_int($pagesize)) {
      return $this->_errorBadRequest('pagesize', 'invalid');
    }

    $query_array = array();
    $query_array['parameters'] = $params;
    $query_array['page'] = $page;
    $query_array['pagesize'] = $pagesize;

    $query = http_build_query($query_array);

    $response = $this->get($url . '?' . $query);
    return $response;
  }

}