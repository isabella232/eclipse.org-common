<?php
/**
 * Copyright (c) 2019 Eclipse Foundation.
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

/**
 * GithubRepository class
 *
 * Usage example:
 *
 * include_once('GithubRepository.class.php');
 * $GithubApi = new GithubRepository();
 *
 * @author ericpoirier
 */
class GithubRepository {

  /**
   * Repository url
   *
   * var string
   */
  public $repo_url = "";

  /**
   * Get the repository's url
   *
   * @return string
   */
  public function getRepoUrl() {
    return $this->repo_url;
  }

  /**
   * Set the repository's url
   *
   * @param string $url
   */
  public function setRepoUrl($url = "") {
    $url = filter_var($url, FILTER_SANITIZE_URL);
    if (!empty($url)) {
      $this->repo_url = $url;
    }
  }

  /**
   * Get the organization's name
   *
   * @return string
   */
  public function getOrganizationName() {
    $repo = $this->repoUrlArray();
    if (empty($repo[0])) {
      return "";
    }
    return $repo[0];
  }

  /**
   * Get the team's name
   *
   * @return string
   */
  public function getContributorTeamName() {
    $repo = $this->repoUrlArray();
    if (empty($repo[0]) || empty($repo[1])) {
      return "";
    }
    return $repo[0] . '-' . $repo[1] . '-contributors';
  }

  /**
   * Get the repository's name
   *
   * @return string
   */
  public function getRepoName() {
    $repo = $this->repoUrlArray();
    if (empty($repo[1])) {
      return "";
    }
    return $repo[1];
  }

  /**
   * Get the array result of the repository's url
   *
   * @return array
   */
  private function repoUrlArray() {
    if (empty($this->repo_url)) {
      return array();
    }
    return explode('/', preg_replace('/http(s|)?:\/\/[www\.]?github.com\//', '', $this->repo_url));
  }
}