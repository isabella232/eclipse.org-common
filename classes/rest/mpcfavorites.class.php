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
   * MPC application token
   *
   * @var string
   */
  private $application_token = 'MZ04RMOpksKN5GpxKXafq2MSjSP';

  /**
   * Mpc favorites application key
   *
   * @var string
   */
  private $application_key = 'mpc_favorites';

  /**
   * Fetch Marketplace favorite(s).
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
  }

  /**
   * Get mpc_favorite user blob.
   *
   * @return bool
   */
  public function fetchFavorite() {
    return $this->getBlob($this->application_token, $this->application_key);
  }

  /**
   * Convert base64 string to array
   *
   * Mpc stores a comas seperated list of nid.
   * This function is used to convert the base64
   * response body into an array.
   *
   * @return array
   */
  public function convertBodyIntoArray(){
    return explode(',', str_replace(' ', '', base64_decode($this->getBody())));;
  }

  /**
   * Add Marketplace favorite(s)
   * @param array $nodes
   *
   * @return bool
   */
  public function addFavorite($nodes = array()) {

    $data = $this->convertBodyIntoArray();

    foreach($nodes as $nid) {
      $data[] = (string)trim($nid);
    }

    $data = array_filter($data);
    // remove duplicates
    $data = array_unique($data);
    // create string
    $data_str = implode(',', $data);
    // base64 encode string
    $data_str_base64 = base64_encode($data_str);

    return $this->putBlob($this->application_token, $this->application_key, $data_str);
  }

  /**
   * Remove Marketplace favorite(s)
   *
   * @param array $nodes
   *
   * @return bool
   */
  public function removeFavorite($nodes = array()) {

    $data = $this->convertBodyIntoArray();

    $new_data = array();
    foreach($data as $key => $nid) {
      if (!in_array($nid, $nodes) && !empty($nid)) {
        $new_data[] = $nid;
      }
    }

    // remove duplicates.
    $new_data = array_filter($new_data);
    $new_data = array_unique($new_data);
    // create string.
    $data_str = implode(',', $new_data);
    // base64 encode string.
    $data_str_base64 = base64_encode($data_str);

    return $this->putBlob($this->application_token, $this->application_key, $data_str);
  }

  /**
   * Fetch Marketplace favorite(s).
   */
  public function getMpcFavoritesIndex($arguments = array()) {
    $arguments['random'] = rand(0, 1000);
    $query = http_build_query($arguments);
    return $this->get('marketplace/favorites?' . $query);
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
    return $this->get('marketplace/favorites/' . $content_id .'?' . $query);
  }

  /**
   * Get favorite count from response of getMpcFavoritesIndex()
   *
   * @param stdClass $data
   * @param unknown $content_id
   *
   * @return bool/int
   */
  public function getFavoriteCountFromResponse($data, $content_id) {
    foreach ($data['mpc_favorites'] as $favorites) {
      if ($favorites['content_id'] == $content_id) {
        return $favorites['count'];
      }
    }
    return FALSE;
  }
}
