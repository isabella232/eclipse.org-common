<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/session.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once("donor.class.php");
require_once("donationEmails.class.php");

/**
 * Store information regarding a donation
 *
 * @author chrisguindon
 */
class Donation {

  /**
   * Eclipse $App()
   */
  private $App = NULL;

  /**
   * Donation amount.
   */
  public $donation_amount = 0;

 /**
  * Donation worthy of Eclipse benefit.
  *
  * @var unknown
  */
  public $donation_benefit = 0;

  /**
   * Donation level / Benefit group
   */
  public $donation_benefit_group = '';

  /**
   * Currency used in donation.
   */
  public $donation_currency = "";


  /**
   * Visibility setting of donation
   */
  public $donation_is_anonymous = 1;

  /**
   * Comment left by the donor for this donation
   */
  public $donation_message = "";

  /**
   * Get random internal invoice id
   *
   * We use this value to fetch the value submitted by the
   * user before they made the donation.
   *
   * @var unknown
   */
  public $donation_random_invoice_id = '';

  /**
   * Payment status of a donation
   *
   * @var unknown
   */
  public $donation_status = "";

  /**
   * Donation landing page
   *
   * @var string
   */
  public $donation_landing_page = NULL;

  /**
   * Donation file_id
   *
   * @var integer
   */
  public $donation_file_id = NULL;

  /**
   * Donation scope
   *
   * @var string
   */
  public $donation_scope = NULL;

  /**
   * Donation campaign
   *
   * @var string
   */
  public $donation_campaign = NULL;

  /**
   * If this is a subscription donation or not.
   *
   * @var unknown
   */
  public $donation_subscription = 0;

  /**
   * Gateway Transaction id
   */
  public $donation_txn_id = '';

  /**
   * Donor object
   */
  public $Donor = NULL;

  public $table_prefix = FALSE;

  public function __construct($test_mode = FALSE)  {
    if ($test_mode === TRUE){
      $this->table_prefix = 'testing_';
    }
    $this->Donor = new Donor($test_mode);
    $this->App = new App();
  }

  /**
   * Validate if the user is passing the right info
   * for linking a donation with an eclipse account
   *
   * @return boolean
   */
  public function link_donation() {
    $txn_id = $this->App->getHTTPParameter('tid', 'get');
    $invoice_id = $this->App->getHTTPParameter('iid', 'get');

    if (!empty($txn_id) && !empty($invoice_id)){
      $this->set_donation_txn_id($txn_id);
      $this->set_donation_random_invoice_id($invoice_id);
      $this->Donor->set_donor_contribution_with_txn_id($this->get_donation_txn_id());
      $cid = $this->Donor->Contribution->getContributionID();
      if (empty($cid)){
        return FALSE;
      }

      if ($this->update_donor_from_process_table()) {
        $this->Donor->Friend->selectFriend($this->Donor->Contribution->getFriendID());
        $this->set_donation_amount($this->Donor->Contribution->getAmount());
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Try to update a donation with an eclipse account
   *
   * @return boolean/string
   */
  public function update_donation() {

    $uid = $this->Donor->get_donor_uid();
    $cfid = $this->Donor->Contribution->getFriendID();
    $fid = $this->Donor->Friend->getFriendID();
    if (!empty($uid) && $cfid == $fid) {
      return 'link_already_done';
    }
    $stage = $this->App->getHTTPParameter('form-stage', 'post');

    // Let's try to link automaticaly, if the user is logged in
    if (empty($stage)){
      $Session = new Session();
      $Friend = $Session->getFriend();
      $email = $Friend->getEmail();
      $this->Donor->set_donor_email($email);
      $uid = $this->Donor->get_donor_uid();
      if (!empty($uid)) {
        $this->get_or_create_friend();
        $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
        $this->Donor->Contribution->updateContribution();
        $DonationEmails = new DonationEmails($this);
        $DonationEmails->send_email();
        return 'updated';
      }
    }

    if ($stage == 'update') {
      $this->Donor->set_donor_email($this->App->getHTTPParameter('email', 'post'));
      $this->set_donation_message($this->App->getHTTPParameter('message', 'post'));
      $this->set_donation_is_anonymous($this->App->getHTTPParameter('is_anonymous', 'post'));
      $uid = $this->Donor->get_donor_uid();
      if (!empty($uid)) {
        $this->get_or_create_friend();
        $this->Donor->Contribution->setMessage($this->get_donation_message());
        $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
        $this->Donor->Contribution->updateContribution();
        $DonationEmails = new DonationEmails($this);
        $DonationEmails->send_email();
        return 'updated';
      }
      return 'invalid_eclipse_id';
    }
    return FALSE;
  }

  /**
   * Get human friendly string of $is_anonymous
   *
   * @return string
   */
  public function get_donation_is_anonymous_string() {
    if (!$this->donation_is_anonymous) {
      return 'Public';
    }
    return 'Private';
  }

  /**
   * Get donation amount
   *
   * @return number
   */
  public function get_donation_amount() {
    $this->_set_donation_benefit_level();
    return $this->donation_amount;
  }

  /**
   * Get donation_benefit value
   */
  public function get_donation_benefit(){
   return (int)$this->donation_benefit;
  }

  /**
   * Get donation_benefit group/level
   */
  public function get_donation_benefit_level() {
    $this->_set_donation_benefit_level();
    return $this->donation_benefit_group;
  }

  /**
   * Set donation benefit level
   *
   * This should only be called when we sent the donation
   * amount.
   */
  private function _set_donation_benefit_level() {
    $amount = $this->donation_amount;
    $currency = $this->get_donation_currency();

    // Access to the Friends of Eclipse mirrors.
    if ($amount >= 35) {
      $this->set_donation_benefit(1);
    }

    // Minimum donation of 250USD or 0.70 BTC
    if ($amount >= 250) {
      $this->donation_benefit_group = 'webmaster_idol';
    }
    // Minumum donation of 100USD or 0.25 BTC
    elseif ($amount >= 50) {
      $this->donation_benefit_group = 'best_friend';
    }
    // Minimum donation of 35USD or 0.15 BTC
    elseif ($amount >= 35) {
      $this->donation_benefit_group = 'friend';
    }
    else{
      $this->donation_benefit_group = 'donor';
    }
  }

  /**
   * Get donation currency type
   */
  public function get_donation_currency() {
    if (empty($this->donation_currency)) {
      $this->donation_currency = 'USD';
    }
    return $this->donation_currency;
  }

  /**
   * Get $is_anonymous value
   *
   * @return number
   */
  public function get_donation_is_anonymous(){
    return (int)$this->donation_is_anonymous;
  }

  /**
   * Get donation message
   *
   * @return string
   */
  public function get_donation_message() {
    return $this->donation_message;
  }

  /**
   * Get donation random invoice id
   *
   * @param number $length
   * @return Ambigous <string, unknown>
   */
  public function get_donation_random_invoice_id() {
    if (empty($this->donation_random_invoice_id)) {
      $this->set_donation_random_invoice_id();
    }
    return $this->donation_random_invoice_id;
  }

  /**
   * Get donation status
   *
   * @return unknown
   */
  public function get_donation_status() {
    if (empty($this->donation_status)){
      $this->set_donation_status('initial');
    }
    return strtoupper($this->donation_status);
  }

  /**
   * Get $donation_landing_page
   *
   * @return string
   */
  public function get_donation_landing_page() {
    if (empty($this->donation_landing_page)){
      $this->set_donation_landing_page('donate');
    }
    return strtoupper($this->donation_landing_page);
  }

  /**
   * Get $donation_file_id
   *
   * @return string
   */
  public function get_donation_file_id() {
    if (!is_numeric($this->donation_file_id)) {
      $this->donation_file_id = NULL;
    }
    return $this->donation_file_id;
  }

  /**
   * Get $donation_scope
   *
   * @return string
   */
  public function get_donation_scope() {
    if (!is_string($this->donation_scope)) {
      $this->donation_scope = NULL;
    }
    return $this->donation_scope;
  }

  /**
   * Get $donation_campaign
   *
   * @return string
   */
  public function get_donation_campaign() {
    if (!is_string($this->donation_campaign)) {
      $this->donation_campaign = NULL;
    }
    return $this->donation_campaign;
  }

  /**
   * Get donation_subscription value
   *
   * @return unknown
   */
  public function get_donation_subscription(){
    return (int)$this->donation_subscription;
  }

  /**
   * Get id for transaction
   * @param string $txn_id
   */
  public function get_donation_txn_id() {
    return $this->donation_txn_id;
  }

 public function get_or_create_friend() {
    $update = FALSE;
    $active_email = $this->Donor->get_active_email();
    $this->Donor->Friend->setEmail($active_email);
    $this->Donor->get_friend_id_from_uid();
    $this->Donor->Friend->setIsAnonymous($this->get_donation_is_anonymous());
    $this->Donor->Friend->setIsBenefit($this->get_donation_benefit());
    $new_friend_id = $this->Donor->Friend->insertUpdateFriend();
    $this->Donor->Friend->setFriendID($new_friend_id);
    $this->Donor->Contribution->setFriendID($new_friend_id);
  }

  /**
   * Set donation amount
   *
   * @param string $donation_amount
   */
  public function set_donation_amount($donation_amount = 0) {

    //Make sure the amount is a number and it's not 0
    if ($donation_amount == "0" or $donation_amount == "" or is_nan($donation_amount)) {
      $donation_amount = 35.00;
    }

    // Format the amount
    $donation_amount = number_format($donation_amount, 2, '.', '');

    $this->donation_amount = $donation_amount;
    $this->_set_donation_benefit_level();
  }

 /**
   * Set donation_benefit value
   */
  public function set_donation_benefit($value) {
    if ($value === 1 || $value === '1' || $value === TRUE) {
      return $this->donation_benefit = 1;
    }
  }

  /**
   * Set donation currency type
   */
  public function set_donation_currency($currency = '') {
    $valid_currency = array('USD');
    $valid_type = array('PAYPAL');
    $currency = strtoupper($currency);
    if (in_array($currency, $valid_currency)) {
      $this->donation_currency = $currency;
    }
    // We might be passing $paymentGateway->gateway_type and
    // we know that we only accept USD for paypal.
    elseif (in_array($currency, $valid_type)) {
      if ($currency == 'PAYPAL') {
         $this->donation_currency = 'USD';
      }
    }
  }

  /**
   * Set $is_anonymous value
   *
   * @param unknown $value
   */
  public function set_donation_is_anonymous($value) {
  $this->donation_is_anonymous = 1;
    if ($value == 'recognition' || ($value === 0 || $value === '0')) {
      $this->donation_is_anonymous = 0;
    }
  }

  /**
   * Set donation message
   *
   * @param unknown $message
   */
  public function set_donation_message($message) {
    $message = filter_var($message, FILTER_SANITIZE_STRING);
    $this->donation_message = strip_tags($message);
  }

  /**
   * Set donation random invoice id
   */
  public function set_donation_random_invoice_id($key = '') {
    $key = filter_var($key, FILTER_SANITIZE_STRING);
    if (empty($key)) {
      $length = 30;
      $keys = array_merge(range(0, 9), range('a', 'z'));

      for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
      }
    }
    $this->donation_random_invoice_id = $key;
  }

  /**
   * Set donation status
   *
   * @param unknown $status
   */
  public function set_donation_status($status) {
    $status = strtolower($status);
    $available_status = array(
      'completed', // paypal payment_status
      'no_eclipse_id', // Completed but addionnal steps are required to link donation with eclipse_id.
      'new_donation_form', // This was created by the process.php script.
    );

    if (in_array($status, $available_status)) {
      $this->donation_status = strtoupper($status);
    }
  }

  /**
   * Set donation $landing_page
   *
   * @param string $page
   */
  public function set_donation_landing_page($page) {
    $page = strtolower($page);
    $available = array(
      'donate', // eclipse.org/donate/
      'download', // eclipse.org/downloads/
      'eclipse_ide', // eclipse.org/donate/ide/
    );

    if (in_array($page, $available)) {
      $this->donation_landing_page = strtoupper($page);
    }
  }

    /**
   * Get $donation_file_id
   *
   * @return string
   */
  public function set_donation_file_id($file_id) {
    if (!empty($file_id) && is_numeric($file_id) && $file_id <= 2147483647){
      $this->donation_file_id = $file_id;
    }

    return $this->donation_file_id;
  }

  /**
   * Get $donation_scope
   *
   * @return string
   */
  public function set_donation_scope($scope = "") {
    if (!empty($scope) && is_string($scope)){
      $this->donation_scope = substr($scope, 0, 128);
    }
    return $this->donation_scope;
  }

  /**
   * Get $donation_campaign
   *
   * @return string
   */
  public function set_donation_campaign($campaign = "") {
    if (!empty($campaign) && is_string($campaign)){
      $this->donation_campaign = substr($campaign, 0, 128);
    }
    return $this->donation_campaign;
  }

  /**
   * Set donation subscription value
   * @param string $donation_subscription
   */
  public function set_donation_subscription($donation_subscription = NULL) {
    $this->donation_subscription = 0;
    if ($donation_subscription) {
      $this->donation_subscription = 1;
    }
  }

  /**
   * Set id for transaction
   * @param string $txn_id
   */
  public function set_donation_txn_id($txn_id = "") {
    $txn_id = filter_var($txn_id, FILTER_SANITIZE_STRING);
    $this->donation_txn_id = $txn_id;
  }

  /**
   * Update donor() & donation() based off the info from the
   * friends_process table.
   *
   * @return bool
   */
  public function update_donor_from_process_table() {
    $unique_id = $this->get_donation_random_invoice_id();
    $sql = 'SELECT /* USE MASTER */ * FROM ' . $this->table_prefix . 'friends_process WHERE id_unique = ';
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($unique_id));
    $sql .= ' LIMIT 1';
    $rs = $this->App->eclipse_sql($sql);
    $process = mysql_fetch_assoc($rs);

    // We found a match in the friends_process table :)
    if (!empty($process)) {
      $this->Donor->set_donor_first_name($process['first_name']);
      $this->Donor->set_donor_last_name($process['last_name']);
      $this->set_donation_message($process['message']);
      $this->set_donation_subscription($process['subscription']);
      $this->set_donation_is_anonymous($process['is_anonymous']);
      $this->set_donation_landing_page($process['landing_page']);
      $this->set_donation_file_id($process['file_id']);
      $this->set_donation_scope($process['scope']);
      $this->set_donation_campaign($process['campaign']);
      $this->Donor->set_donor_email($process['email']);
      $this->Donor->set_donor_paypal_email($process['email_paypal']);
      $this->Donor->set_donor_uid($process['uid']);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Update/create Contribution from the IPN script
   *
   * @param string $update
   */
  public function update_donation_from_ipn($update = TRUE) {
    $this->get_or_create_friend();
    if ($this->Donor->Contribution->getContributionID() == "") {
      // Contribution Doesn't Already Exist
      $this->Donor->Contribution->setAmount($this->get_donation_amount());
      $this->Donor->Contribution->setMessage($this->get_donation_message());
      $this->Donor->Contribution->setTransactionID($this->get_donation_txn_id());
      $this->Donor->Contribution->setProcessId($this->get_donation_random_invoice_id());
      $this->Donor->Contribution->setCurrency($this->get_donation_currency());
      $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
      $this->Donor->Contribution->insertContribution();
    }
    else{
     // Update transaction. This should not append...
      //$this->Donor->Contribution->setProcessId($this->get_donation_random_invoice_id());
      $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
      $this->Donor->Contribution->updateContribution();
    }

    // Send out Emails
    $DonationEmails = new DonationEmails($this);
    $content = $DonationEmails->send_email();
  }

}
