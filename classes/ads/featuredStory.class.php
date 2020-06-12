<?php

/**
 * Copyright (c) 2019 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

class FeaturedStory {

  public $data = array();

  /**
   * Get a featured story based on start and end date
   *
   * @return array
   */
  public function getFeaturedStory($type = 'both') {

    $data = $this->getXmlData();
    if (empty($data['item'])) {
      return array();
    }

    $default_item = array();
    $valid_items = array();
    foreach ($data['item'] as $item) {
      if (!empty($item['default'])) {
        $default_item = $item;
      }

      if (isset($item['type']) && $item['type'] !== NULL && $item['type'] !== 'both' && $type !== $item['type']) {
        continue;
      }

      if(time() >= strtotime($item['start_date']) && time() < strtotime($item['end_date'])) {
        $valid_items[] = $item;
      }
    }

    // If we should add back up items and the XML
    // did not return any valid or default items
    if (empty($default_item) && empty($valid_items)) {
      $valid_items = $this->getBackupItems();
    }

    // Return the featured items if the array is not empty
    if (!empty($valid_items)) {
      $featured_item = $valid_items[array_rand($valid_items)];
      if (!empty($featured_item)) {
        return $featured_item;
      }
    }

    // Otherwise return the default item
    return $default_item;
  }

  /**
   * Get back up items to be printed in the featured story section
   *
   * @return array
   */
  private function getBackupItems() {
    $backup_items = array();
    $backup_items[] = array(
      'prefix' => '',
      'title' => '<h2><strong>Sign up to the Eclipse Newsletter</strong></h2>',
      'body' => '<p>A fresh new issue delivered monthly</p>',
      'link' => '<a class="btn btn-primary btn-lg" href="https://eclipsecon.us6.list-manage.com/subscribe/post">Subscribe</a>',
      'bg_image' => 'images/2019-06-bg.jpg'
    );
    $backup_items[] = array(
      'prefix' => '',
      'title' => '<h2><strong>Donate to the Eclipse Foundation</strong></h2>',
      'body' => '<p>Power the Eclipse Community with your donation</p>',
      'link' => '<a class="btn btn-primary btn-lg" href="https://www.eclipse.org/donate/">Donate</a>',
      'bg_image' => 'images/2019-06-bg.jpg'
    );

    return $backup_items;
  }

  /**
   * Get the featured story data from the source file
   *
   * @return array
   */
  public function getXmlData() {
    return $this->data;
  }

  /**
   * Set the featured story data from the source file
   *
   * @param string $xml_file_path
   */
  public function setXmlData($xml_file_path) {

    if (empty($xml_file_path)) {
      return FALSE;
    }

    $fileContents = file_get_contents($xml_file_path);
    if (empty($fileContents)) {
      return FALSE;
    }

    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $simpleXml = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (empty($simpleXml)) {
      return FALSE;
    }

    $featured_stories_json = json_encode($simpleXml);
    if (empty($featured_stories_json)) {
      return FALSE;
    }

    $this->data = json_decode($featured_stories_json,TRUE);
    return TRUE;
  }
}