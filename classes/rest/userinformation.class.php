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
 * Eric Poirier (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */
require_once ('lib/eclipseussblob.class.php');

/**
 * CommitterPaperwork class
 *
 * Usage example:
 *
 * include_once('userInformation.class.php');
 * $UserInformation = new UserInformation();
 *
 * @author chrisguindon
 */
class UserInformation extends EclipseUSSBlob {

  /**
   * Retrieve a user's information
   *
   * @param string $identifier
   *
   * @return array
   */
  public function retrieveUserInfromation($identifier = "") {
    if (empty($identifier)) {
      return array();
    }
    $response = $this->get("account/profile/" . $identifier);
    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Retrieve a user's projects
   *
   * @param string $identifier
   *
   * @return array
   */
  public function retrieveUserProjects($identifier = "") {
    if (empty($identifier)) {
      return array();
    }
    $response = $this->get('account/profile/' . $identifier. '/projects');
    $this->unsetHeader('If-Match');
    return $response;
  }
}