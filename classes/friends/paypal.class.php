<?php
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Chris Guindon (Eclipse Foundation)- initial API and implementation
 *    Edouard Poitars (Eclipse Foundation)- Heavy modifications for new donatin process
 *******************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/evt_log.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");
include('paypal.class.inc.php');
define('PAYPAL_AUTH_TOKEN', $auth_token);

class Paypal {
  // CONFIG: Enable $this->debug mode. This means we'll log requests into 'ipn.log' in the same directory.
  // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
  // Set this to 0 once you go live or don't require logging.
  private $debug = FALSE;
  private $use_sandbox = FALSE;
  private $log_file = "/tmp/ipn.log";
  private $database_logging = TRUE;
  private $show_all = FALSE;
  private $paypal_url = PAYPAL_URL;
  private $paypal_donation_email = PAYPAL_DONATION_EMAIL;
  private $auth_token = PAYPAL_AUTH_TOKEN;
  private $anonymous = 'Private';
  private $comment = "";
  private $itemname = "";
  private $email = "";
  private $firstname = "";
  private $lastname = "";
  private $amount = 0;
  private $transaction_id = "";
  private $payment_status = "";
  private $benefit = FALSE;
  private $transaction = array();
  private $status_check = array('Completed', 'Pending');
  private $status_message = "";

  public function get_email() {
    return $this->email;
  }

  public function get_comment() {
    return $this->comment;
  }

  public function get_show_all(){
    return $this->show_all;
  }

  public function get_status_message() {
    return $this->status_message;
  }

  public function get_transaction_data() {
    $this->_set_transaction();
    return $this->transaction;
  }

  public function get_first_name() {
    return $this->firstname;
  }

  public function set_first_name($name) {
    $this->firstname = $name;
  }

  public function get_last_name() {
    return $this->lastname;
  }

  public function set_last_name($name) {
    $this->lastname = $name;
  }

  public function get_anonymous_string() {
    if (!$this->anonymous) {
      return 'Public';
    }
    return 'Private';
  }

  public function get_paypal_url() {
    return $this->paypal_url;
  }

  public function get_donation_email() {
    return $this->paypal_donation_email;
  }

  public function set_email($_email) {
    $this->email = $_email;
  }

  public function set_comment($comment) {
    $this->comment = strip_tags($comment);
  }

  public function set_anonymous($value) {
    $this->anonymous = $value;
  }

  private function _set_transaction(){
    $this->transaction = array(
      'email' => $this->email,
      'anonymous' => $this->anonymous,
      'comment' => $this->comment,
      'itemname' => $this->itemname,
      'firstname' => $this->firstname,
      'lastname' => $this->lastname,
      'amount' => $this->amount,
      'transaction_id' => $this->transaction_id,
      'payment_status' => $this->payment_status,
      'benefit' => $this->benefit,
    );
  }

  private function _set_status_message($status) {
    if ($this->debug) {
      switch ($status) {
        case ECLIPSE_PAYPAL_MSG_SUCCESSFUL_UPDATE:
          $this->status_message .= '<div class="success">Your donation information was successfully updated!</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_ERROR_UPDATE:
          $this->status_message .= '<div class="error">An error has occured. The webmaster team was contacted.</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_WARNING_DEBUG:
          $this->status_message .= '<div class="warning">Warning: Debug & logging mode is enabled.</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_WARNING_SANDBOX:
          $this->status_message .= '<div class="warning">Warning: Sandbox mode is enabled.</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_SHOW_ALL_MODE:
          $this->status_message .= '<div class="warning">Warning: Show All mode is enabled.</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_IPN_VALID:
          $this->status_message .= '<div class="success">Success: Valid IPN response.</div>';
          break;
        case ECLIPSE_PAYPAL_MSG_IPN_INVALID:
          $this->status_message .= '<div class="error">Error: Invalid IPN response.</div>';
          break;
      }
    } else {
      $this->status_message = '';
    }
  }

  public function set_show_all($showall = FALSE){
    if ($showall) {
       $this->_set_status_message(ECLIPSE_PAYPAL_MSG_SHOW_ALL_MODE);
    }
    $this->show_all = $showall;
  }

  public function set_debug_mode($debug = FALSE){
    if ($debug) {
      $this->_set_status_message(ECLIPSE_PAYPAL_MSG_WARNING_DEBUG);
    }
    $this->debug = $debug;
  }

  public function set_sandbox_mode($sandbox = FALSE){
    if ($sandbox) {
      $this->_set_status_message(ECLIPSE_PAYPAL_MSG_WARNING_SANDBOX);
      $this->paypal_url = PAYPAL_SANDBOX_URL;
      $this->paypal_donation_email = PAYPAL_SANDBOX_DONATION_EMAIL;
      $this->auth_token = PAYPAL_SANDBOX_AUTH_TOKEN;
    }
    $this->use_sandbox = $sandbox;
  }

  public function set_logging_mode($database_logging = TRUE) {
    $this->database_logging = $database_logging;
  }

  private function log($message) {
    // File Logging
    if ($this->debug) {
      error_log($message, 3, $this->log_file);
    }
  }

  private function log_database($action) {
    // Database Logging
    if ($this->database_logging) {
      $EvtLog = new EvtLog();
      $EvtLog->setLogTable("__paypal.class");
      if ($this->transaction) $EvtLog->setPK1("$this->transaction_id,$this->amount,$this->payment_status");
      else $EvtLog->setPK1("Unknown");
      $ip = $_SERVER['REMOTE_ADDR'];
      $EvtLog->setPK2($ip);
      $EvtLog->setLogAction($action);
      if ($this->email) $EvtLog->insertModLog($this->email);
      else $EvtLog->insertModLog("Unknown");
    }
  }

  private function _parse_meta_data($data) {
    $this->itemname = filter_var($data['item_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $this->firstname = filter_var($data['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $this->lastname = filter_var($data['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $this->email = filter_var($data['payer_email'], FILTER_SANITIZE_EMAIL);
    $this->amount = filter_var($data['mc_gross'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    if (strpos($this->amount, ".") == 0) {
      $this->amount = $this->amount . ".00";
    }
    $this->transaction_id = filter_var($data['txn_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $this->payment_status = filter_var($data['payment_status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // Got to love PHP - $this->amount is a string but this still works flawlessly
    $this->benefit = ($this->amount >= 35) ? TRUE : FALSE;
  }

  public function confirm_donation() {
    return $this->request_transaction_information();
  }

  public function request_transaction_information() {
    $this->log(date('[Y-m-d H:i e] '). "Requesting transaction information" . PHP_EOL);
    $tx_token = $_GET['tx'];
    if (!$tx_token) $tx_token = $_POST['txn_id'];
    $req = 'cmd=_notify-synch&tx=' . urlencode($tx_token) . '&at=' . urlencode($this->auth_token);
    $res = $this->curl_request($this->paypal_url, $req);
    if (strpos($res, "SUCCESS\n") !== FALSE) {
      $lines = explode("\n", $res);
      $data = array();
      foreach ($lines as $line) {
        $value = explode('=', $line);
        $data[urldecode($value[0])] = urldecode($value[1]);
      }
      $this->_parse_meta_data($data);
      $this->_set_transaction();
      $this->log_database('DONATION_CONFIRMED');
      return TRUE;
    } else {
      $this->log_database('DONATION_INVALID');
      $this->payment_status = 'Error';
      // Sending the paypal response for debugging
      mail('friends@eclipse.org', 'DONATION_INVALID', $res);
    }
    return FALSE;
  }

  private function curl_request($url, $req) {
    $ch = curl_init($url);
    if ($ch == FALSE) {
      $this->log(date('[Y-m-d H:i e] ') . 'Error while initializing CURL ' . PHP_EOL);
      return FALSE;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    // CONFIG: Optional proxy configuration
    curl_setopt($ch, CURLOPT_PROXY, PROXY);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    // Set TCP timeout to 30 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
    // of the certificate as shown below. Ensure the file is readable by the webserver.
    // This is mandatory for some environments.
    //$cert = __DIR__ . "./cacert.pem";
    //curl_setopt($ch, CURLOPT_CAINFO, $cert);
    $res = curl_exec($ch);
    if (curl_errno($ch) != 0) { // cURL error
      //$this->log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL);
      curl_close($ch);
      return FALSE;
    } else {
      $this->log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL);
      $this->log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL);
      // Split response headers and payload
      list($headers, $res) = explode("\r\n\r\n", $res, 2);
      curl_close($ch);
      return $res;
    }
  }
}
