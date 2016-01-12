<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

class Messages {

  private $messages = array();

  /**
   * This function sets the message
   * @param $name - string containing the name of the message
   * @param $msg  - string containing the message itself
   * @param $type - string containing the type of the message
   * */
  public function setMessages($name = "", $msg = "", $type = "") {
    $allowed_type = array(
      'success',
      'info',
      'warning',
      'danger'
    );
    if (in_array($type, $allowed_type) && !empty($msg) && !empty($name)) {
      $this->messages[$name][$type][] = $msg;
    }
  }

  /**
   * This function returns the Messages
   * @param $msg - array containing the names, types and content of each messages
   * @return string
   * */
  public function getMessages() {
    $return = "";
    if (!empty($this->messages)) {
      foreach ($this->messages as $type) {
        foreach ($type as $key => $value) {
          $list = '<ul>';
          if (count($value) == 1) {
            if ($key == 'danger'){
              $org_value = $value[0];
              $value[0] = '<p><strong>' . $org_value . '</strong></p>';
            }
            $return .= $this->_getMessageContainer($value[0], $key);
            continue;
          }
          foreach ($value as $msg) {
            $list .= '<li><strong>' . $msg . '</strong></li>';
          }
          $list .= '</ul>';
          $return .= $this->_getMessageContainer($list, $key);
        }
      }
    }
    return $return;
  }

  /**
   * This function returns a DIV tag containing the $message with the proper CSS class
   * @param $message - String containing the message
   * @param $type    - String containing the message type
   *                   Accepted types: success, info, warning, danger
   * @return string
   * */
  private function _getMessageContainer($message = '', $type = 'success') {
    $class = "stay-visible alert alert-" . $type;
    return '<div class="' . $class . '" role="alert">' . $message . '</div>';
  }
}