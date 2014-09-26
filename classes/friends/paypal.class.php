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
 *******************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/evt_log.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");

define('ECLIPSE_PAYPAL_MSG_SUCCESSFUL_UPDATE', 0);
define('ECLIPSE_PAYPAL_MSG_ERROR_UPDATE', 1);
define('ECLIPSE_PAYPAL_MSG_WARNING_DEBUG', 2);
define('ECLIPSE_PAYPAL_MSG_WARNING_SANDBOX', 3);
define('ECLIPSE_PAYPAL_MSG_SHOW_ALL_MODE', 4);
define('ECLIPSE_PAYPAL_MSG_IPN_VALID', 5);
define('ECLIPSE_PAYPAL_MSG_IPN_INVALID', 6);
define('PROXY', 'proxy.eclipse.org:9899');
define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('PAYPAL_SANDBOX_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYPAL_AUTH_TOKEN', $auth_token);
define('PAYPAL_SANDBOX_AUTH_TOKEN', 'T-vs7NBkZlK-c10lW4aP9TGLOuhInTv2ZoGXGqBHp3CSZ6uEHiIN8lyaeq0');
define('PAYPAL_DONATION_EMAIL', 'donate@eclipse.org');
define('PAYPAL_SANDBOX_DONATION_EMAIL', 'business@eclipse.org');
define('PAYPAL_PURCHASE_CMD', '_xclick');
//define('PAYPAL_PURCHASE_CMD', '_donations');
// https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/

class Paypal {
  // CONFIG: Enable $this->debug mode. This means we'll log requests into 'ipn.log' in the same directory.
  // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
  // Set this to 0 once you go live or don't require logging.
  private $debug = FALSE;
  private $use_sandbox = FALSE;
  private $log_file = "/tmp/ipn.log";
  //private $database_logging = TRUE;
  private $database_logging = FALSE;
  private $show_all = FALSE;
  private $paypal_url = PAYPAL_URL;
  private $paypal_donation_email = PAYPAL_DONATION_EMAIL;
  private $auth_token = PAYPAL_AUTH_TOKEN;
  private $bugzilla_email = "";
  private $anonymous = 'Private';
  private $comment = "";
  private $itemname = "";
  private $firstname = "";
  private $lastname = "";
  private $amount = 0;
  private $transaction_id = "";
  private $payment_status = "";
  private $benefit = FALSE;
  private $transaction = array();
  private $status_check = array('Completed', 'Pending');
  private $status_message = "";

  public function get_bugzilla_email() {
    return $this->bugzilla_email;
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

  public function set_bugzilla_email($bemail) {
    $this->bugzilla_email = $bemail;
  }

  public function set_comment($comment) {
    $this->comment = strip_tags($comment);
  }

  public function set_anonymous($value) {
    $this->anonymous = $value;
  }

  private function _set_transaction(){
    $this->transaction = array(
      'bugzilla_email' => $this->bugzilla_email,
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
    $this->$database_logging = $database_logging;
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
      $EvtLog->setPK1($this->bugzilla_email);
      $ip = $_SERVER['REMOTE_ADDR'];
      $EvtLog->setPK2("$ip,$this->itemname,$this->amount");
      $EvtLog->setLogAction($action);
      $EvtLog->insertModLog($this->transaction_id);
    }
  }

  public function validate_transaction() {
    $this->log(date('[Y-m-d H:i e] ') . 'Starting transaction validation process' . PHP_EOL);
    // Read POST data
    // reading posted data directly from $_POST causes serialization
    // issues with array data in POST. Reading raw POST data from input stream instead.
    $this->log(date('[Y-m-d H:i e] ') . 'Parsing raw post data' . PHP_EOL);
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
      $keyval = explode ('=', $keyval);
      if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
      $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
      if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
      } else {
        $value = urlencode($value);
      }
      $req .= "&$key=$value";
    }
    // Post IPN data back to PayPal to validate the IPN data is genuine
    // Without this step anyone can fake IPN data
    $this->log(date('[Y-m-d H:i e] ') . 'Posting IPN data back to Paypal to validate' . PHP_EOL);
    $res = curl_request($this->paypal_url, $req);
    if ($res == FALSE) { exit('CURL Error'); }
    $this->ipn_validate($res);

  function ipn_validation($res) {
      // Inspect IPN validation result and act accordingly
      if (strcmp ($res, "VERIFIED") == 0) {
        $this->log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL);
        $this->_set_status_message(ECLIPSE_PAYPAL_MSG_IPN_VALID);
        //set a cookie for 279-days to block the donation page if the user made a donation
        setcookie ("thankyou_page[donation]", TRUE, time() + (3600 * 24 * 279), '/', '.eclipse.org');
        $this->_parse_meta_data($_POST);
        $this->_process_donation();
      } else if (strcmp ($res, "INVALID") == 0) {
        // log for manual investigation
        // Add business logic here which deals with invalid IPN messages
        $this->_set_status_message(ECLIPSE_PAYPAL_MSG_IPN_INVALID);
        $this->log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL);
      }
    }
  }

  private function _parse_meta_data($data) {
    $this->itemname = $data['item_name'];
    $this->firstname = $data['first_name'];
    $this->lastname = $data['last_name'];
    $this->amount = $data['mc_gross'];
    if (strpos($this->amount, ".") == 0) {
      $this->amount = $this->amount . ".00";
    }
    $this->transaction_id = $data['txn_id'];
    $this->payment_status = $data['payment_status'];
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
      return TRUE;
    } else {
      $this->payment_status = 'Error';
    }
    return FALSE;
  }

  private function _process_donation(){
    $this->log(date('[Y-m-d H:i e] '). "Processing donation" . PHP_EOL);
    $this->_set_transaction();
    if (in_array($this->payment_status, $this->status_check)) {
      // Check to see if this transaction has already been processed.
      $checkContribution = new Contribution();
      $check_trans = $checkContribution->selectContributionExists($this->transaction_id);
      if ($check_trans == FALSE) {
        //Check to see if user already exists in friends
        $checkFriends = new Friend();
        $bugzilla_id = $checkFriends->getBugzillaIDFromEmail($this->bugzilla_email);
        $friend_id = $checkFriends->selectFriendID("bugzilla_id", $bugzilla_id);
        // Lets Update the Friend Information
        $Friend = new Friend();
        $Friend->setFirstName($this->firstname);
        $Friend->setLastName($this->lastname);
        $Friend->setBugzillaID($bugzilla_id);
        $Friend->setIsAnonymous($this->anonymous);
        $Friend->setIsBenefit($this->benefit);
        $Friend->setFriendID($friend_id);
        $friend_id = $Friend->insertUpdateFriend();
        $Contribution = new Contribution();
        if ($friend_id != 0) {
          $Contribution->setFriendID($friend_id);
        }
        $Contribution->setAmount($this->amount);
        $Contribution->setMessage($this->comment);
        $Contribution->setTransactionID($this->transaction_id);
        $Contribution->insertContribution();
        $this->log_database('DONATION_SUCCESSFUL');
        $this->log(date('[Y-m-d H:i e] '). "Contribution processed" . PHP_EOL);
      }
    } else { // Transaction not processed yet
      $this->log(date('[Y-m-d H:i e] '). "Transaction not processed by Paypal yet" . PHP_EOL);
    }
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
