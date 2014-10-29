<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

class SolsticeBase {

  /**
   * Validate variable
   *
   * Return an empty variable if argument is empty or
   * wrong type.
   *
   * @param mixed $var
   * @param string $type
   *
   * @return string
   */
  public function is_var($var, $type = 'string') {
    switch ($type) {
      case 'string':
        if (!empty($var) && is_string($var)) {
          return $var;
        }
        return "";
        break;

      case 'array':
        if (!empty($var) && is_array($var)) {
          return $var;
        }
        return array();
        break;

      case 'bool':
        if (!empty($var) && is_bool($var)) {
          return $var;
        }
        return FALSE;
        break;
    }
  }
}