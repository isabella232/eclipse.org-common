<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
require_once("mailchimp.class.php");

class Subscriptions extends Mailchimp {

  function __construct(App $App) {
    parent::__construct($App);
  }

}