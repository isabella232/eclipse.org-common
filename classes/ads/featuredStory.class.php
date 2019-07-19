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
  public function getFeaturedStory() {

    $data = $this->getXmlData();
    if (empty($data['item'])) {
      return array();
    }

    $default_item = array();
    $valid_items = array();
    foreach ($data['item'] as $item) {
      if (empty($item['start_date']) && empty($item['end_date'])) {
        $default_item = $item;
        continue;
      }
      if(time() >= strtotime($item['start_date']) && time() < strtotime($item['end_date'])) {
        $valid_items[] = $item;
      }
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