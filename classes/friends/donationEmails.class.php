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
require_once("donation.class.php");

/**
 * Send out Donation Emails
 *
 * @author chrisguindon
 */
class DonationEmails {

  /**
   * Eclipse App class
   *
   * @var stdClass
   */
  private $App = NULL;

  /**
   * Donation Object
   *
   * @var stdClass
   */
  private $Donation = NULL;

  /**
   * Stores different types of codes
   * to use in FOE emails
   *
   * @var array
   */
  private $email_code = array();

  /**
   * The e-mail content
   *
   * @var unknown
   */
  private $email_content = '';

  /**
   * Link for the friends of eclipse
   * @var unknown
   */
  private $email_foe_logo_link = '';

  /**
   * E-mail headers
   *
   * @var unknown
   */
  private $email_headers = '';

  /**
   * Receiving e-mail address
   *
   * @var string
   */
  private $email_to = '';

  /**
   * Store the code and url for a
   * free eclipse t-shirt
   *
   * @var array
   */
  private $email_tshirt_code = array();

  /**
   * Set a table prefix for debug mode
   *
   * @var string
   */
  private $table_prefix = "";

  /**
   * Debug state
   *
   * @var bool
   */
  private $test_mode = FALSE;

  /**
   * Contructor
   *
   * @param stdClass $Donation
   */
  public function DonationEmails($Donation) {
    $this->App = new App();
    $this->Donation = $Donation;
    if ($Donation->table_prefix == 'testing_') {
      $this->_set_test_mode(TRUE);
    }
    $this->_set_email_headers('From: Eclipse Webmaster (automated) <webmaster@eclipse.org>'. PHP_EOL .'Bcc: donate@eclipse.org'. PHP_EOL .'Content-Type: text/plain; charset=UTF-8');
    $this->_set_email_foe_logo_link('https://dev.eclipse.org/site_login/myaccount.php');
    $this->_set_email_code('ECLIPSECON', 'FRIEND');
    $this->_set_email_code('OREILLY', 'PCBW');
  }

  /**
   * Send out donation email
   *
   * This function will determine if an e-mail can be sent
   * and also send out the correct email based of the
   * donation amount.
   */
  public function send_email() {
    $this->_set_email_to($this->Donation->Donor->get_active_email());
    $email_to = $this->_get_email_to();
    if (empty($email_to)) {
      // We dont have an e-mail address from the donation
      return FALSE;
    }

    $transaction_id = $this->Donation->get_donation_txn_id();
    $amount = $this->Donation->get_donation_amount();
    $uid = $this->Donation->Donor->get_donor_uid();
    $level = $this->Donation->get_donation_benefit_level();

    if (empty($uid) && $level != 'donor') {
      // We dont have an eclipse account, we need an extra
      // step from the donor before we can apply benefits.
      $this->_get_email_link_eclipse_account();
    }
    else {
      switch ($level) {
        case 'donor':
          $this->_get_email_donor();
          break;

        case 'friend':
          $this->_get_email_friend();
          break;

        case 'best_friend':
          $this->_get_email_best_friend();

          break;

        case 'webmaster_idol':
          $this->_get_email_webmaster_idol();
          break;
      }
    }

    $EventLog = new EvtLog();
    if (!empty($this->email_to) && !empty($this->email_content)) {
      if ($this->_get_test_mode()) {
        $this->email_content .= PHP_EOL . PHP_EOL . '--TEST MODE--' .PHP_EOL . PHP_EOL;
        ob_start();
        print 'This email is addressed to: ' . $this->_get_email_to() .PHP_EOL . PHP_EOL;
        print_r($this);
        $this->email_content .= ob_get_clean();
      }
      mail($this->_get_email_to(),  "Thank You For Your Donation", $this->_get_email_content(), $this->_get_email_headers());
      $EventLog->setPK1($this->_get_email_to());
      $EventLog->setLogAction("DONATION_EMAIL_SENT");
    }
    else {
      $EventLog->setPK1($level);
      $EventLog->setLogAction("DONATION_EMAIL_NOT_SENT");
    }
    $EventLog->setLogTable("__paypal.class"); // To make browsing the log table easier
    $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
    $EventLog->insertModLog($transaction_id);
    return $this->email_content;
  }

  /**
   * Get email code from $email_code array
   *
   * The type could be anything, it's usually set in
   * the constructor
   *
   * @param string $type
   */
  private function _get_email_code($type) {
    if (!isset($this->email_code[$type])) {
      return '';
    }
    return $this->email_code[$type];
  }

  /**
   * Get the e-mail content message
   */
  private function _get_email_content(){
    return $this->email_content;
  }

  /**
   * Get the link for the foe logo page
   */
  private function _get_email_foe_logo_link(){
    return $this->email_foe_logo_link;
  }

  /**
   * Get the default email headers
   *
   * @return string
   */
  private function _get_email_headers(){
    return $this->email_headers;
  }

  /**
   * Get the e-mail adress of the donor
   */
  private function _get_email_to(){
    return $this->email_to;
  }

  /**
   * Get the the code or url for a free t-shirt
   *
   * @param array $type
   *   The type is either "code" or "url"
   *
   * @return string
   */
  private function _get_email_tshirt_code($type) {
    // Check for an existing code for this transaction
    if (!isset($this->email_tshirt_code[$type])) {
      $this->_get_email_existing_tshirt_code();
    }

    // Try to get a new one
    if (!isset($this->email_tshirt_code[$type])) {
      $this->_get_email_new_tshirt_code();
    }

    // We're out of t-shirt code !!
    if (!isset($this->email_tshirt_code[$type])) {
     return '';
    }

    // We found a t-shirt code! Hip Hip Hurray :)
    return $this->email_tshirt_code[$type];
  }

  /**
   * Get testmode status
   * @return boolean
   */
  private function _get_test_mode() {
    return $this->test_mode;
  }

  /**
   * Email for linking an eclipse account with a donation
   *
   * This is needed for a donation from an e-mail that is
   * not an eclipse account.
   */
  private function _get_email_link_eclipse_account(){
    $this->email_content = $this->_get_email_greeting_string();
    $this->email_content .= "Thank you for your donation! We are pleased to welcome you to the Friends of Eclipse program. To take full advantage of the program you will need to create an Eclipse account. This will allow you access to the Friends of Eclipse benefits." . PHP_EOL . PHP_EOL;
    $domain = $this->App->getEclipseDomain();
    $query = array(
      'tid' => $this->Donation->get_donation_txn_id(),
      'iid' => $this->Donation->get_donation_random_invoice_id(),
    );
    $query_string = http_build_query($query);
    $this->email_content .= "To create your Eclipse account go to: https://" . $domain['domain']  . "/donate/link-account.php?" . $query_string  . PHP_EOL . PHP_EOL;
    $this->email_content .= "Best Regards,\n\nMike Milinkovich\nExecutive Director\nEclipse Foundation" . PHP_EOL . PHP_EOL;
  }

  /**
   * Email for a donation of without any benefits
   *
   * This is usually a donation for less than 35US,
   *
   */
  private function _get_email_donor() {
    $this->email_content = $this->_get_email_greeting_string();
    $this->email_content .= "Thank you for your donation." . PHP_EOL . PHP_EOL;
    $this->email_content .= $this->_get_email_footer_string();
  }

  /**
   * Email for a friend of eclipse
   *
   * This is for a donation between 35USD and 99.99USD
   */
  private function _get_email_friend() {
    $email = $this->_get_email_greeting_string();
    $email .= "Thank you for your donation. We are happy to welcome you as a Friend of Eclipse." . PHP_EOL . PHP_EOL;
    $email .= "In recognition of your donation, we are pleased to provide the following Friend of Eclipse benefits:" . PHP_EOL . PHP_EOL;
    $email .= "\t-\tYou may use the Friend of Eclipse logo. It can be found here: " . $this->_get_email_foe_logo_link() . PHP_EOL;
    $email .= "\t-\tYou get access to the Mirror Site while downloading Eclipse when logged in." . PHP_EOL;
    $email .= "\t-\tYou get 40% off prints & 50% off ebooks at http://oreilly.com using the discount code: " . $this->_get_email_code('OREILLY') . PHP_EOL . PHP_EOL;
    $email .= $this->_get_email_footer_string();
    $this->email_content = $email;
  }

  /**
   * Email for a best friend of Eclipse
   *
   * This is for a donation between 100USD and 249.99USD
   */
  private function _get_email_best_friend() {
    $email = $this->_get_email_greeting_string();
    $email .= "Thank you for your generous donation. We are happy to welcome you as a Best Friend of Eclipse." . PHP_EOL . PHP_EOL;
    $email .= "In recognition of your donation, we are pleased to provide the following Best Friend of Eclipse benefits:" . PHP_EOL . PHP_EOL;
    $email .= "\t-\tYou may use the Friend of Eclipse logo. It can be found here: " . $this->_get_email_foe_logo_link() . PHP_EOL;
    $email .= "\t-\tYou get access to the Mirror Site while downloading Eclipse when logged in." . PHP_EOL;
    $email .= "\t-\tYou get 40% off prints & 50% off ebooks at http://oreilly.com using the discount code: " . $this->_get_email_code('OREILLY') . PHP_EOL;
    $email .= "\t-\tYou qualify for a Best Friend discount to attend an EclipseCon conference. The amount depends on the event but use the discount code: " . $this->_get_email_code('ECLIPSECON') . PHP_EOL;
    $email .= $this->_get_email_tshirt_code_message();
    $email .= $this->_get_email_footer_string();
    $this->email_content = $email;
  }

  /**
   * Email for a webmaster idol
   *
   * This is for a donation of 250USD or more
   */
  private function _get_email_webmaster_idol() {
    //@todo: Confirm with roxanne that best_friend email and webmaster e-mail is the same.
    return $this->_get_email_best_friend();
  }

  /**
   * Get the email message string for an Eclipse t-shirt
   */
  private function _get_email_tshirt_code_message() {
    $code = $this->_get_email_tshirt_code('url');
    if (!empty($code)) {
      return  "\t-\tYou get an Eclipse t-shirt! Claim it here: " . $code . PHP_EOL . PHP_EOL;
    }
    else {
     // Let roxanne know that we are out of t-shirts.
     $headers = 'From: Eclipse Webdev (automated) <webdev@eclipse.org>' . PHP_EOL . 'Content-Type: text/plain; charset=UTF-8';
     $content = 'We are currently out of t-shirt codes. This following user is eligible for a t-shirt. Please contact him manually as soon as possible.';
     ob_start();
     print_r($this);
     $content .= ob_get_clean();
     mail('friends@eclipse.org',  "T-shirt code table is empty", $content, $headers);

     return  "\t-\tYou get an Eclise t-shirt, but we are currently out of stock. We will contact you once our stock is replenished." . PHP_EOL . PHP_EOL;
    }
  }

  /**
   * Fetch a new t-shirt code
   *
   * This function retreive a t-shirt code and
   * consume the code in the database to avoid
   * sending the same code to more than 1 donor.
   */
  private function _get_email_new_tshirt_code() {
    $sql = "SELECT code, url FROM " . $this->Donation->table_prefix . "tshirts WHERE sent_date IS NULL LIMIT 1";
    $code = mysql_fetch_assoc($this->App->eclipse_sql($sql));
    if (!empty($code)) {
      $this->_set_email_tshirt_code($code);
      $this->_update_email_tshirt_consume_code();
    }
  }

  /**
   * Try to find a t-shirt code for a transaction
   */
  private function _get_email_existing_tshirt_code() {
    $sql = "SELECT code, url FROM " . $this->Donation->table_prefix . "tshirts WHERE transaction_id = " .
    $this->App->returnQuotedString($this->App->sqlSanitize($this->Donation->get_donation_txn_id())) .
    " ORDER BY sent_date DESC LIMIT 1";
    $code = mysql_fetch_assoc($this->App->eclipse_sql($sql));

    if (!empty($code)) {
      $this->_set_email_tshirt_code($code);
    }
  }

  /**
   * Get greeting line for donation Emails
   */
  private function _get_email_greeting_string() {
    $full_name = $this->Donation->Donor->get_donor_full_name();
    if (!empty($full_name)) {
      return "Dear " . $this->Donation->Donor->get_donor_full_name() . "," . PHP_EOL . PHP_EOL;
    }
    return "Hello," . PHP_EOL . PHP_EOL;
  }

  private function _get_email_footer_string() {
    $email = "The Eclipse Foundation relies upon our users' generosity to make Eclipse a great place for open source software development. On behalf of the entire community, thank you for making it possible." . PHP_EOL . PHP_EOL;
    $email .= "Best Regards,\n\nMike Milinkovich\nExecutive Director\nEclipse Foundation" . PHP_EOL . PHP_EOL;
    $email .= "http://www.eclipse.org/donate/\nfriends@eclipse.org" . PHP_EOL . PHP_EOL;
    $email .= "P.S. We have a Corporate Sponsor program for companies that want to support the Eclipse community.  Please encourage your employer to contribute to the success of open source software at Eclipse.\nhttp://www.eclipse.org/corporate_sponsors/";
    return $email;
  }

  /**
   * Set email code
   *
   * @param string $type
   * @param string $code
   */
  private function _set_email_code($type, $code){
    $this->email_code[$type] = $code;
  }

  /**
   * Set email message
   * @param string $content
   */
  private function _set_email_content($content){
    $this->email_content = $content;
  }

  /**
   * Set friend of eclipse logo link
   *
   * @param string $url
   */
  private function _set_email_foe_logo_link($url){
    $this->email_foe_logo_link = $url;
  }

  /**
   * Set default e-mail headers
   *
   * @param string $headers
   */
  private function _set_email_headers($headers){
    $this->email_headers = $headers;
  }

  /**
   * Set e-mail to
   * @param string $email_to
   */
  private function _set_email_to($email_to){
    $this->email_to = $email_to;
  }

  /**
   * Set email t-shirt code
   *
   * @param unknown $code
   */
  private function _set_email_tshirt_code($code){
    $this->email_tshirt_code = $code;
  }

  /**
   * Set test mode
   *
   * @param bool $testmode
   */
  private function _set_test_mode($testmode = FALSE) {
    $this->test_mode = $testmode;
  }

  /**
   * Consume a t-shirt code from the database
   */
  private function _update_email_tshirt_consume_code() {
    $sql = "UPDATE " . $this->Donation->table_prefix . "tshirts SET
      sent_date = CURDATE(),
      uid = " .$this->App->returnQuotedString($this->App->sqlSanitize($this->Donation->Donor->get_donor_uid())). ",
      transaction_id = " .$this->App->returnQuotedString($this->App->sqlSanitize($this->Donation->get_donation_txn_id())). ",
      bugzilla_id = " .$this->App->returnQuotedString($this->App->sqlSanitize($this->Donation->Donor->Friend->getBugzillaID()))
    . " WHERE code = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_get_email_tshirt_code('code')));
    $this->App->eclipse_sql($sql);
  }
}
