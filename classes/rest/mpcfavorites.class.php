<?php
/*******************************************************************************
* Copyright (c) 2016 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Christopher Guindon (Eclipse Foundation) - Initial implementation
*******************************************************************************/
require_once('lib/eclipseussblob.class.php');

/**
 * MpcFavorites class
 *
 * Usage example:
 *
 *  include_once('mpcfavorites.class.php');
 *  $EclipseUSS = new MpcFavorites();
 *  $EclipseUSS->loginSSO();
 *  $EclipseUSS->addFavorite(array('1','2,'9')));
 *  $EclipseUSS->removeFavorite(array('1', '2'));
 *  $EclipseUSS->logout();
 *
 * @author chrisguindon
 */
class MpcFavorites extends EclipseUSSBlob{

  /**
   * Mpc Blob object
   * @var obj
   */
  private $MpcBlob = NULL;

  /**
   * Fetch Marketplace favorite(s).
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->MpcBlob = $this->_getNewMpcBlob();
  }

  /**
   * Get mpc_favorite user blob.
   *
   * @return bool
   */
  public function fetchFavorite() {
    if (!$this->loginSSO()) {
      return FALSE;
    }

    $data = $this->getBlob($this->MpcBlob->application_token, $this->MpcBlob->key, $this->MpcBlob->etag);
    $this->MpcBlob->response = $data;

    // The curl request failed.
    if (isset($data->error)) {
      $this->MpcBlob->state = 'error';
      return FALSE;
    }

    switch ($data->code) {
      // Not Found
      case '404':
        $this->MpcBlob->state = 'new';
        $_SESSION['marketplace']['user']['mpc_favorites'] = $this->MpcBlob;
        return TRUE;

      // User blob found
      case '200':
      case '304':
        $this->MpcBlob->state = 'update';
        // Body is expected to be empty on a 304
        // but since it hasn't change, we do not need to
        // update it.
        if (!empty($data->body)) {
          $body = json_decode($data->body);
          $this->MpcBlob->etag = $body->etag;
          $this->MpcBlob->value = $body->value;
          $this->MpcBlob->decoded_value = explode(',', str_replace(' ', '', base64_decode($body->value)));
          $this->MpcBlob->url_suffix = $this->_removeBaseUrlFromUrl($body->url);
          $this->MpcBlob->url = $body->url;
          $_SESSION['marketplace']['user']['mpc_favorites'] = $this->MpcBlob;
        }
        return TRUE;
    }

    $this->MpcBlob->state = 'error';
    return FALSE;
  }

  /**
   * Add Marketplace favorite(s)
   * @param array $nodes
   *
   * @return bool
   */
  public function addFavorite($nodes = array()) {

    if (!$this->_preprocessMpcRequest($nodes)) {
      return FALSE;
    }

    $data = $this->MpcBlob->decoded_value;
    foreach($nodes as $nid) {
      $data[] = (string)trim($nid);
    }

    // remove duplicates
    $data = array_unique($data);
    // create string
    $data_str = implode(',', $data);
    // base64 encode string
    $data_str_base64 = base64_encode($data_str);

    $response = $this->putBlob($this->MpcBlob->application_token, $this->MpcBlob->key, $this->MpcBlob->etag, $data_str);
    $this->MpcBlob->response = $response;
    // The update was successful.
    // Ignore 304, since the server did not need to perform an update
    switch ($response->code) {
      case '200':
      case '201':
        $this->MpcBlob->state = 'update';
        $this->MpcBlob->etag = $response->headers['Etag'];
        $this->MpcBlob->value = $data_str_base64;
        $this->MpcBlob->decoded_value = $data;
        $_SESSION['marketplace']['user']['mpc_favorites'] = $this->MpcBlob;
        return TRUE;
      case '409':
        // This is our second attempt, an unexpected error
        // is occuring.
        if ($this->MpcBlob->state === 'retry') {
          return FALSE;
        }
        // We tried to create or update a blob
        // but we need to update an existing blob instead
         $this->fetchFavorite();
         $this->MpcBlob->state = 'retry';
         return $this->removeFavorite($nodes);
    }

    return FALSE;
  }

  /**
   * Remove Marketplace favorite(s)
   *
   * @param array $nodes
   *
   * @return bool
   */
  public function removeFavorite($nodes = array()) {

    if (!$this->_preprocessMpcRequest($nodes)) {
      return FALSE;
    }

    $data = $this->MpcBlob->decoded_value;
    $new_data = array();
    foreach($data as $key => $nid) {
      if (!in_array($nid, $nodes) && !empty($nid)) {
        $new_data[] = $nid;
      }
    }

    // remove duplicates.
    $new_data = array_unique($new_data);
    // create string.
    $data_str = implode(',', $new_data);
    // base64 encode string.
    $data_str_base64 = base64_encode($data_str);

    $response = $this->putBlob($this->MpcBlob->application_token, $this->MpcBlob->key, $this->MpcBlob->etag, $data_str);

    $this->MpcBlob->response = $response;
    // The update was successful.
    // Ignore 304, since the server did not need to perform an update
    switch ($response->code) {
      case '200':
      case '201':
        $this->MpcBlob->state = 'update';
        $this->MpcBlob->etag = $response->headers['Etag'];
        $this->MpcBlob->value = $data_str_base64;
        $this->MpcBlob->decoded_value = $new_data;
        $_SESSION['marketplace']['user']['mpc_favorites'] = $this->MpcBlob;
        return TRUE;
      case '409':
        // This is our second attempt, an unexpected error
        // is occuring.
        if ($this->MpcBlob->state === 'retry') {
          return FALSE;
        }
        // We tried to create or update a blob
        // but we need to update an existing blob instead
        $this->fetchFavorite();
        $this->MpcBlob->state = 'retry';
        return $this->addFavorite($nodes);
    }

    return FALSE;
  }

  public function resetFavorites() {

    // Try to login using sso if the user is not
    // currently logged in.
    if (!$this->isAuthenticated()) {
      if (!$this->loginsso()) {
        return FALSE;
      }
    }

    // Fetch mpc_favorites if it's unprocessed.
    if ($this->MpcBlob->state === "unprocessed") {
      if (!$this->fetchFavorite()) {
        return FALSE;
      }
    }

    if ($this->MpcBlob->state === 'error') {
      return FALSE;
    }

    $response = $this->deleteBlob($this->MpcBlob->application_token, $this->MpcBlob->key, $this->MpcBlob->etag);
    $this->MpcBlob->response = $response;
    // The update was successful.
    // Ignore 304, since the server did not need to perform an update
    switch ($response->code) {
      case '204':
      case '404':
        $_SESSION['marketplace']['user']['mpc_favorites'] = NULL;
        $this->MpcBlob = $this->_getNewMpcBlob();
        $_SESSION['marketplace']['user']['mpc_favorites'] = $this->MpcBlob;
        return TRUE;
      case '409':
        // This is our second attempt, an unexpected error
        // is occuring.
        if ($this->MpcBlob->state === 'retry') {
          return FALSE;
        }
        // We tried to create or update a blob
        // but we need to update an existing blob instead
        $this->fetchFavorite();
        $this->MpcBlob->state = 'retry';
        return $this->resetFavorites();
    }

    return FALSE;
  }

  /**
   * Fetch Marketplace favorite(s).
   */
  public function getMpcFavoritesIndex($arguments = array()) {
    $arguments['random'] = rand(0, 1000);
    $query = http_build_query($arguments);
    $data = $this->get('mpc_favorites?' . $query);

    switch ($data->code) {
      case '200':
      case '304':
        if (!empty($data->body)) {
          return json_decode($data->body);
        }
        break;
    }

    return FALSE;
  }

  /**
   * Get list of users who favorited a listing
   *
   * @param number $content_id
   * @param array $arguments
   *
   * @return bool/stdClass
   */
  public function getMpcFavorites($content_id = 0, $arguments = array()) {
    $arguments['random'] = rand(0, 1000);
    $query = http_build_query($arguments);
    $data = $this->get('mpc_favorites/' . $content_id .'?' . $query);

    switch ($data->code) {
      case '200':
      case '304':
        if (!empty($data->body)) {
          return json_decode($data->body);
        }
        break;
    }

    return FALSE;
  }

  /**
   * Get favorite count from response of getMpcFavoritesIndex()
   *
   * @param stdClass $data
   * @param unknown $content_id
   *
   * @return bool/int
   */
  public function getFavoriteCountFromResponse(stdClass $data, $content_id) {
    foreach ($data->mpc_favorites as $favorites) {
      if ($favorites->content_id == $content_id) {
        return $favorites->count;
      }
    }
    return FALSE;
  }

  private function _preprocessMpcRequest($nodes) {

    if (!is_array($nodes) || empty($nodes)) {
      return FALSE;
    }

    // Try to login using sso if the user is not
    // currently logged in.
    if (!$this->isAuthenticated()) {
      if (!$this->loginsso()) {
        return FALSE;
      }
    }

    // Fetch mpc_favorites if it's unprocessed.
    if ($this->MpcBlob->state === "unprocessed") {
      if (!$this->fetchFavorite()) {
        return FALSE;
      }
    }

    if ($this->MpcBlob->state === 'error') {
      return FALSE;
    }

    return TRUE;
  }
  /**
   * Create/Reset MpcBlob object
   *
   * @return obj $MpcBlob
   */
  private function _getNewMpcBlob() {
    if (isset($_SESSION['marketplace']['user']['mpc_favorites']) &&
      is_object($_SESSION['marketplace']['user']['mpc_favorites'])) {
      return $_SESSION['marketplace']['user']['mpc_favorites'];
    }
    $MpcBlob = new stdClass();
    $MpcBlob->state = 'unprocessed';
    $MpcBlob->url = '';
    $MpcBlob->url_suffix = '';
    $MpcBlob->application_token = 'MZ04RMOpksKN5GpxKXafq2MSjSP';
    $MpcBlob->key = 'mpc_favorites';
    $MpcBlob->etag = '';
    $MpcBlob->value = '';
    $MpcBlob->decoded_value = array();
    $MpcBlob->response = NULL;
    return $MpcBlob;
  }
}

