<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
require_once("subscriptions_base.class.php");

define('MAILCHIMP_SUBSCRIBE','subscribe');
define('MAILCHIMP_UNSUBSCRIBE','unsubscribe');

class Mailchimp extends Subscriptions_base {

  private $api_key = FALSE;

  private $subscribe_list = array();

  private $list_id = FALSE;

  public function __construct(App $App) {
    parent::__construct($App);

    // Checking if the user is changing Subscription status
    $stage = filter_var($this->App->getHTTPParameter('stage', 'POST'), FILTER_SANITIZE_STRING);
    $form = filter_var($this->App->getHTTPParameter('form_name', 'POST'), FILTER_SANITIZE_STRING);

     if ($form === 'mailchimp_form') {
       if ($stage === 'mailchimp_subscribe') {
         if (!$this->addUserToList()) {
           die('The subscription service is unavailable at the moment.');
         }
       }

      if ($stage === 'mailchimp_unsubscribe') {
         if (!$this->_removeUserFromList()) {
           die('The subscription service is unavailable at the moment.');
         }
       }
    }
  }


  /**
   * Add user to mailing list
   *
   * @return bool
   */
  public function addUserToList() {
    if (!$this->getIsSubscribed()) {
      $email_md5 = $this->_getEmailMd5();
      $list_id = $this->_getListId();
      if ($email_md5 && $list_id) {
        $request = array(
          'action' => 'PUT',
          'endpoint' => "/lists/" . $list_id . "/members/" . $email_md5,
          'data' => array(
            "email_address" => $this->getEmail(),
            "status_if_new" => "subscribed",
            "merge_fields" => array(
                "FNAME" => $this->getFirstName(),
                "LNAME" => $this->getLastName(),
            ),
          ),
        );

        $data = $this->_curlRequest($request);
        if ($data === TRUE) {
          // Add to list if there's no error
          $this->_addUserToSubscribeList();
          $this->App->setSystemMessage('mailchimp_unsubscribe', 'You have successfully subscribed to Eclipse Newsletter.', 'success');
          return TRUE;
        }
      }
    }
    $this->App->setSystemMessage('mailchimp_unsubscribe', 'There was a problem subscribing you to Eclipse Newsletter. (#subscriptions-001)', 'danger');
    return FALSE;
  }

  /**
   * This function returns the user's subscription status
   *
   * @return bool
   */
  public function getIsSubscribed() {
    if (!isset($this->subscribe_list[$this->getEmail()])) {
      $this->_verifyUserSubscription();
    }
    return $this->subscribe_list[$this->getEmail()];
  }

  /**
   * Get HTML form
   *
   * @return string
   */
  public function output(){
    $uid = $this->Friend->getUID();
    $html = "";
    if (!empty($uid)) {
      ob_start();
      include 'tpl/subscriptions.tpl.php';
      $html = ob_get_clean();
    }

    return $html;
  }

  /**
   * Add user to subscribe list
   */
  private function _addUserToSubscribeList() {
    $this->subscribe_list[$this->getEmail()] = TRUE;
  }


  /**
   * This function sends an API request to Mailchimp
   *
   * @param $action - string containing the words GET, PUT or DELETE
   *
   * @return array
   */
  private function _curlRequest($request) {

    $accepted_actions = array(
      'GET',
      'DELETE',
      'PUT'
    );

    $return = array();
    if (!empty($request['action']) && in_array($request['action'], $accepted_actions) && !empty($request['endpoint'])) {
      $url = $this->_mailchimpUrl() . $request['endpoint'];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: apikey ' . $this->_getApiKey()));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
      curl_setopt($ch, CURLOPT_ENCODING, '');

      curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

      // CONFIG: Optional proxy configuration
      curl_setopt($ch, CURLOPT_PROXY, 'proxy.eclipse.org:9899');
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

      // If we're on staging
      if ($this->getDebugMode()) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_PROXY, '');
      }

      switch ($request['action']) {
        case "DELETE":
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
          $ret = curl_setopt($ch, CURLOPT_HEADER, TRUE);
          $result = curl_exec($ch);
          $result = curl_getinfo($ch);
          break;
        case "PUT":
          if (!empty($request['data'])) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request['data']));
            $result = curl_exec($ch);
          }
          break;
        case "GET":
          curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query(array()));
          $result = curl_exec($ch);
          break;

      }

      curl_close($ch);
      if (isset($result)) {
        if ($request['action'] !== 'DELETE') {
          $result = json_decode($result, TRUE);
        }
        $result = $this->_validate_results($result, $request);
        if (is_bool($result)) {
          return $result;
        }
      }
    }
    return 'ERROR';
  }

  /**
   * Get Api key
   *
   * @return string
   */
  private function _getApiKey(){
    if (empty($this->api_key)) {
       $this->_setApiKeyAndListId();
    }

    return $this->api_key;
  }

  /**
   * Get MD5 hash of the user's e-mail
   *
   * @return string|bool
   */
  private function _getEmailMd5(){
    $email = $this->getEmail();
    if (!empty($email)) {
      return md5($email);
    }
    return FALSE;
  }

  /**
   * Get List id
   * @return string|unknown|boolean
   */
  private function _getListId() {
    if (empty($this->list_id)) {
       $this->_setApiKeyAndListId();
    }

    return $this->list_id;
  }


  /**
   * This function assemble the correct API url to send requests to
   *
   * @return string
   * */
  private function _mailchimpUrl() {
    if ($key = $this->_getApiKey()) {
      $datacentre = explode('-', $key);
      return 'https://' . $datacentre[1] . '.api.mailchimp.com/3.0/';
    }
  }


  /**
   * Remove user from mailing list.
   */
  private function _removeUserFromList() {

    if ($this->getIsSubscribed()) {
      $email_md5 = $this->_getEmailMd5();
      $list_id = $this->_getListId();
      if ($email_md5 && $list_id) {
        $request = array(
          'action' => 'DELETE',
          'endpoint' => "/lists/". $list_id ."/members/" . $email_md5,
        );

        $data = $this->_curlRequest($request);

        if ($data === TRUE) {
          // Remove from list if there's no error
          $this->_removeUserFromSubscribeList();
          $this->App->setSystemMessage('mailchimp_unsubscribe', 'You have successfully unsubscribed to Eclipse Newsletter.', 'success');
          return TRUE;
        }
      }
    }
    $this->App->setSystemMessage('mailchimp_unsubscribe', 'There was a problem unsubscribing you to Eclipse Newsletter. (#subscriptions-001)', 'danger');
    return FALSE;
  }

  /**
   * Remove user from subscribe list
   */
  private function _removeUserFromSubscribeList() {
    $this->subscribe_list[$this->getEmail()] = FALSE;
  }

  /**
   * This function sets the Mailchimp API Key and List ID
   *
   * The default API key and List ID are fetched from eclipse-php-classes
   */
  private function _setApiKeyAndListId() {
    require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");

    $mode = "production";
    if ($this->getDebugMode() === TRUE) {
      $mode = "staging";
    }

    if (empty($mailchimp_keys[$mode]['api_key']) || empty($mailchimp_keys[$mode]['list_id'])) {
      $this->App->setSystemMessage('mailchimp_api_key', 'The Mailchimp API key or List Id is not valid', 'danger');
      return FALSE;
    }

    $this->api_key = $mailchimp_keys[$mode]['api_key'];
    $this->list_id = $mailchimp_keys[$mode]['list_id'];

  }


  /**
   * Validate curl request results
   *
   * @param array $return
   * @param array $request
   *
   * @return sting|bool
   */
  private function _validate_results($return, $request) {
    switch ($request['action']) {
      case "DELETE":
        if ($return['http_code'] == '204') {
          return TRUE;
        }
        break;

      case "PUT":
       if ($return['email_address'] == $this->getEmail() && $return['status'] === 'subscribed') {
          return TRUE;
        }
        break;

      case "GET":
        // The user is not subscribed.
        if ($return['status'] == '404') {
          return FALSE;
        }

        //The user was found in the list.
        if ($return['email_address'] == $this->getEmail() && $return['status'] === 'subscribed') {
          return TRUE;
        }
    }

    // If something goes wrong
    return 'ERROR';
  }

  /**
   * This function verifies if the user is part of the members list
   *
   * @return bool
   * */
  private function _verifyUserSubscription() {
    $email_md5 = $this->_getEmailMd5();
    $list_id = $this->_getListId();
    if ($email_md5 && $list_id) {
       $request = array(
         'action' => 'GET',
         'endpoint' => '/lists/' . $list_id . '/members/' . $email_md5,
       );

      $list = $this->_curlRequest($request);

      if ($list === TRUE) {
        $this->_addUserToSubscribeList();
      }
      elseif ($list === FALSE) {
        $this->_removeUserFromSubscribeList();
      }
    }
  }
}

