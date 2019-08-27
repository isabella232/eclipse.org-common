<?php
/**
 * Copyright (c) 2019 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation)  - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

class SimultaneousRelease {

  public $file_path = "";

  /**
   * Get the New and Noteworthy links from the list of projects
   *
   * @return array
   */
  function getNewAndNoteworthyLinks() {
    $projects = $this->getProjects();
    if (empty($projects)) {
      return array();
    }

    if (!function_exists("sortByProjectName")) {
      function sortByProjectName($a, $b) {
        $a = $a['project_name'];
        $b = $b['project_name'];
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
      }
    }
    uasort($projects, 'sortByProjectName');

    $links = array();
    foreach ($projects as $project) {
      if (empty($project['project_name']) || empty($project['new_and_noteworthy_url']) || !filter_var($project['new_and_noteworthy_url'], FILTER_VALIDATE_URL)) {
        continue;
      }
      $links[] = '<a href="'. $project['new_and_noteworthy_url'] .'">'. $project['project_name'] .'</a>';
    }
    return $links;
  }

  /**
   * Get a list of the projects that contributed to the Simultaneous Release
   *
   * @return array
   */
  public function getProjects() {
    $path = $this->getFilePath();
    if (empty($path)) {
      return array();
    }
    $json_data = json_decode(file_get_contents($path), TRUE);
    if (!empty($json_data)) {
      $json_data = reset($json_data);
      if (!empty($json_data['projects'])) {
        return $json_data['projects'];
      }
    }
    return array();
  }

  /**
   * Set the file path
   *
   * @param string $path
   *
   * @return bool
   */
  public function setFilePath($path) {

    if (empty($path) || !file_exists($path)) {
      return FALSE;
    }

    $this->file_path = $path;
    return TRUE;
  }

  /**
   * Get the file path
   *
   * @return string
   */
  public function getFilePath() {
    return $this->file_path;
  }

}
