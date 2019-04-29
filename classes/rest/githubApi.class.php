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
require_once ('lib/eclipseussblob.class.php');

/**
 * GithubApi class
 *
 * Usage example:
 *
 * include_once('githubApi.class.php');
 * $GithubApi = new GithubApi();
 * $GithubApi->loginSSO();
 *
 * @author ericpoirier
 */
class GithubApi extends EclipseUSSBlob {

  /**
   * Constuctor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->setBaseUrl("https://api.github.com");
  }

  /**
   * Get a organization's teams
   *
   * @param string $org
   * @param string $team_id
   *
   * @return object
   */
  function getOrganizationTeams($org = "", $team_id = "") {

    if (empty($org) || !is_string($org)) {
      return NULL;
    }

    $url = 'orgs/' . $org . '/teams';
    if (!empty($team_id) && is_numeric($team_id)) {
      $url .= "/" . $team_id;
    }

    $response = $this->get($url);

    return $response;
  }

  /**
   * Get a list of members for a specific organization
   *
   * @param string $org
   *
   * @return object
   */
  function getOrganizationMembers($org = "") {
    if (empty($org) || !is_string($org)) {
      return NULL;
    }

    $response = $this->get("orgs/" . $org . "/members");

    return $response;
  }

  /**
   * Validate if a user is a member of an organization
   *
   * @param string $org
   * @param string $username
   *
   * return object
   */
  function validateOrganizationMembership($org = "", $username = "") {
    if (empty($org) || !is_string($org)) {
      return NULL;
    }

    if (empty($username) || !is_string($username)) {
      return NULL;
    }

    $response = $this->get("orgs/" . $org . "/members/" . $username);

    return $response;
  }

  /**
   * Get the contributors team from an organization
   *
   * @param string @org
   *
   * @return array
   */
  function getOrganizationContributorsTeam($org = "") {
    $teams_request = $this->getOrganizationTeams($org);

    if (empty($teams_request) || empty($teams_request->body) || $teams_request->code !== 200) {
      return FALSE;
    }

    $teams = drupal_json_decode($teams_request->body);

    $team = array();
    foreach ($teams as $team_array) {
      if (!empty($team_array['slug']) && strpos($team_array['slug'], 'contributors') !== FALSE) {
        $team = $team_array;
        break;
      }
    }

    if (empty($team["id"])) {
      return FALSE;
    }

    return $team;
  }

  /**
   * Get a team based on the team ID
   *
   * @param string $team_id
   *
   * @return object
   */
  function getTeam($team_id = "") {

    if (empty($team_id) || !is_numeric($team_id)) {
      return NULL;
    }

    return $this->get('teams/' . $team_id);
  }

  /**
   * Get a user based on the provided username
   *
   * @param string $username
   *
   * @return object
   */
  function getUser($username = "") {

    if (empty($username) || !is_string($username)) {
      return NULL;
    }

    return $this->get('users/' . $username);
  }

  /**
   * Get a team's members
   *
   * @param string $team_id
   *
   * return object
   */
  function getTeamMembers($team_id = "", $username = "") {

    if (empty($team_id) || !is_numeric($team_id)) {
      return NULL;
    }

    $url = 'teams/' . $team_id . '/members';
    if (!empty($username) && is_string($username)) {
      $url .= '/' . $username;
    }

    return $this->get($url);
  }

  /**
   * Get a list of pending invitations to become a member to a team
   *
   * @param string $team_id
   *
   * @return object
   */
  function getPendingTeamMembers($team_id = "") {

    if (empty($team_id) || !is_numeric($team_id)) {
      return NULL;
    }

    return $this->get('teams/' . $team_id . '/invitations');
  }

  /**
   * Get a Github user
   *
   * @param string $username
   *
   * @return object
   */
  function getUsers($username = "") {

    if (empty($username) || !is_string($username)) {
      return NULL;
    }

    return $this->get('users/' . $username);
  }

  /**
   * Create a team member in a specific team
   *
   * @param string $team_id
   * @param string $username
   *
   * @return object
   */
  function createTeamMember($team_id = "", $username = "") {

    if (empty($team_id) || !is_numeric($team_id) || empty($username) || !is_string($username)) {
      return NULL;
    }

    return $this->put('teams/' . $team_id . '/memberships/' . $username);
  }

  /**
   * Remove a team member from a specific team
   *
   * @param string $team_id
   * @param string $username
   *
   * @return object
   */
  function removeTeamMember($team_id = "", $username = "") {

    if (empty($team_id) || !is_numeric($team_id) || empty($username) || !is_string($username)) {
      return NULL;
    }

    return $this->delete('teams/' . $team_id . '/members/' . $username);
  }

  /**
   * Create a team in a specific organization
   *
   * @param string $org
   * @param array $data
   *
   * @return object
   */
  function createTeam($org = "", $data = "") {

    if (empty($org) || empty($data) || !is_string($org) || !is_array($data)) {
      return NULL;
    }

    return $this->post("orgs/" . $org . "/teams", json_encode($data));
  }

  /**
   * Remove a team
   *
   * @param string $team_id
   *
   * @return object
   */
  function removeTeam($team_id = "") {

    if (empty($team_id) || !is_numeric($team_id)) {
      return NULL;
    }

    return $this->delete("teams/" . $team_id);
  }

  /**
   * Add or update a repo in a team
   *
   * @param string $team_id
   * @param string $owner
   * @param string $repo
   *
   * @return object
   */
  function updateTeamRepo($team_id = "", $owner = "", $repo = "") {

    if (empty($team_id) || !is_numeric($team_id) || empty($owner) || !is_string($owner) || empty($repo) || !is_string($repo)) {
      return NULL;
    }

    return $this->put("teams/" . $team_id . "/repos/" . $owner . "/" . $repo);
  }

  /**
   * Remove a repo from a team
   *
   * @param string $team_id
   * @param string $owner
   * @param string $repo
   *
   * @return object
   */
  function removeTeamRepo($team_id = "", $owner = "", $repo = "") {

    if (empty($team_id) || !is_numeric($team_id) || empty($owner) || !is_string($owner) || empty($repo) || !is_string($repo)) {
      return NULL;
    }

    return $this->delete("teams/" . $team_id . "/repos/" . $owner . "/" . $repo);
  }
}