<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once(realpath(dirname(__FILE__) . "/../friends/friend.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/session.class.php"));
require_once("accountCreator.class.php");
require_once('/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php');
require_once(realpath(dirname(__FILE__) . "/../../system/evt_log.class.php"));
require_once(realpath(dirname(__FILE__) . "/../captcha/captcha.class.php"));
require_once(realpath(dirname(__FILE__) . "/../forms/formToken.class.php"));

define('SITELOGIN_EMAIL_REGEXP', '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/');

define('SITELOGIN_NAME_REGEXP', '/[^\p{L}\p{N}\-\.\' ]/u');

class Sitelogin {

  private $App = NULL;

  private $agree = "";

  private $bio = "";

  private $Captcha = NULL;

  private $country = "";

  private $country_list = NULL;

  private $githubid = "";

  private $formToken = NULL;

  private $Friend = NULL;

  private $fname = "";

  private $exipred_pass_token = FALSE;

  private $interests = "";

  private $jobtitle = "";

  private $Ldapconn = NULL;

  private $lname = "";

  private $messages = array();

  private $newsletter_status = "";

  private $organization = "";

  private $p = "";

  private $page = "";

  private $password = "";

  private $password1 = "";

  private $password2 = "";

  private $password_update = 0;

  private $password_expired = "";

  private $path_public_key = "";

  private $profile_default = array();

  private $referer = "";

  private $remember = "";

  private $Session = NULL;

  private $stage = "";

  private $submit = "";

  private $takemeback = "";

  private $t = "";

  private $twitter_handle = "";

  private $username = "";

  private $user_uid = "";

  private $user_mail = "";

  private $website = "";

  private $xss_patterns = array();

  private $is_committer = "";

  private $changed_employer = "";

  function Sitelogin($stage = NULL) {
    $this->xss_patterns = array(
      '/<script[^>]*?>.*?<\/script>/si',
      '/<[\/\!]*?[^<>]*?>/si',
      '/<style[^>]*?>.*?<\/style>/siU',
      '/<![\s\S]*?–[ \t\n\r]*>/'
    );

    $this->path_public_key = "/home/data/httpd/dev.eclipse.org/html/public_key.pem";

    global $App;
    $this->App = $App;
    $this->Captcha = New Captcha();
    $this->Session = $this->App->useSession();
    $this->Friend = $this->Session->getFriend();
    $this->Ldapconn = new LDAPConnection();
    $this->FormToken = new FormToken();

    $this->_sanitizeVariables();
    $this->user_uid = $this->Ldapconn->getUIDFromMail($this->Friend->getEmail());
    $this->user_mail = $this->Friend->getEmail();
    $this->is_committer = $this->Friend->getIsCommitter();
    $this->password_expired = $this->_verifyIfPasswordExpired();

    $this->_setStage($stage);

    switch ($this->stage) {
      case 'login':
        $this->_userAuthentification();
        break;
     case 'create':
        $this->_createAccount();
        break;
      case 'reset':
        $this->_resetPassword();
        break;
      case 'reset2':
        $this->_resetPassword2();
        break;
      case 'reset3':
        $this->_resetPassword3();
        break;
      case 'confirm':
        $this->_confirmAccount();
        break;
      case 'save':
        $this->_processSave();
        break;
      case 'save-account':
        $this->_processSave(FALSE);
        break;
      case 'save-profile':
        $this->_processSaveProfile();
        break;
    }
  }

  public function getDomain() {
    $domain = $this->App->getEclipseDomain();
    return 'https://' . $domain['dev_domain'];
  }

  public function getStage(){
    return $this->stage;
  }

  public function getIsCommitter(){
    return $this->is_committer;
  }

  public function getCountryList() {
    if (is_null($this->country_list)) {
      $this->_fetchCountries();
    }
    return $this->country_list;
  }

  public function getSystemMessage() {
    $return = "";
    $allowed_type = array(
      'success',
      'info',
      'warning',
      'danger'
    );
    foreach ($this->messages as $type) {
      foreach ($type as $key => $value) {
        if (!in_array($key, $allowed_type)) {
          continue;
        }
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
    return $return;
  }

  public function getVariables($type = NULL){

    $return = array(
      'agree' => "",
      'username' => "",
      'password' => "",
      'remember' => "",
      'submit' => "",
      'takemeback' => "",
      'githubid' => "",
      'referer' => "",
      'password1' => "",
      'password2' => "",
      'password_update' => "",
      'fname' => "",
      'lname' => "",
      'githubid' => "",
      'organization' => "",
      'jobtitle' => "",
      'website' => "",
      'bio' => "",
      'interests' => "",
      'twitter_handle' => "",
      'country' => "",
      'newsletter_status' => "",
    );

    $this->_get_default_profile_fields();
    # Bug 428032 - Multiple XSS on site_login
    $username = filter_var($this->username, FILTER_SANITIZE_EMAIL);
    $fname = filter_var($this->fname, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $lname = filter_var($this->lname, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $takemeback = filter_var($this->takemeback, FILTER_SANITIZE_ENCODED);
    $remember = filter_var($this->remember, FILTER_SANITIZE_NUMBER_INT);
    $agree = filter_var($this->agree, FILTER_SANITIZE_NUMBER_INT);
    $password_update = filter_var($this->password_update, FILTER_SANITIZE_NUMBER_INT);
    $githubid = filter_var($this->Ldapconn->getGithubIDFromMail($this->Friend->getEmail()), FILTER_SANITIZE_STRING);
    $organization = filter_var($this->organization, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $country = filter_var($this->country, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $jobtitle = filter_var($this->jobtitle, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $website = filter_var($this->website, FILTER_SANITIZE_URL);
    $bio = filter_var($this->bio, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $interests = filter_var($this->interests, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $token = filter_var($this->t, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $twitter_handle = filter_var($this->twitter_handle, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $newsletter_status = filter_var($this->newsletter_status, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);

    switch ($type) {
      case 'login':
        $return['username'] = $username;
        $return['remember'] = ($remember) ? 'checked="checked"' : "";
        $return['takemeback'] = $takemeback;
        break;

      case 'welcomeback':
        $return['username'] = $this->_get_default_field_value('username', $username);
        $return['fname'] = $this->_get_default_field_value('fname', $fname);
        $return['lname'] = $this->_get_default_field_value('lname', $lname);
        $return['githubid'] = $this->_get_default_field_value('githubid', $githubid);
        $return['takemeback'] = $takemeback;
        $return['organization'] = $organization;
        $return['jobtitle'] = $jobtitle;
        $return['website'] = $website;
        $return['bio'] = $bio;
        $return['country'] = $country;
        $return['interests'] = $interests;
        $return['twitter_handle'] = $twitter_handle;
        $return['friend'] = array(
          'uid' => $this->Friend->getUID(),
          'is_committer' => $this->Friend->getIsCommitter(),
          'is_benefit' => $this->Friend->getIsBenefit(),
          'date_joined' => substr($this->Friend->getDateJoined(), 0, 10),
          'date_expired' => substr($this->Friend->getBenefitExpires(), 0, 10),
        );

        break;

      case 'create':
        if ($this->stage == 'create') {
          $return['username'] = $username;
          $return['fname'] = $fname;
          $return['lname'] = $lname;
          $return['organization'] = $organization;
          $return['country'] = $country;
          $return['agree'] =  $agree;
          $return['takemeback'] = $takemeback;
          $return['newsletter_status'] = $newsletter_status;
        }
        break;

      case 'reset':
          $return['token'] = $token;
        break;

      case 'logout':
          $return['password_update'] = $password_update;
        break;

    }
    return $return;
  }

  public function logout() {
    $referer = "";
    if (isset($_SERVER['HTTP_REFERER'])) {
      $referer = $_SERVER['HTTP_REFERER'];
    }

    $eclipse_domains = array(
      'projects.eclipse.org' => 'https://projects.eclipse.org/',
      'eclipse.org/forums/' => 'https://www.eclipse.org/forums/',
      'wiki.eclipse.org' => 'https://wiki.eclipse.org/index.php?title=Special:UserLogout',
      'git.eclipse.org/r/' => 'https://git.eclipse.org/r/',
      'bugs.eclipse.org/bugs/' => 'https://bugs.eclipse.org/bugs/',
      'lts.eclipse.org' => 'https://lts.eclipse.org/',
      'marketplace.eclipse.org' => 'https://marketplace.eclipse.org',
    );

    $redirect = 'https://www.eclipse.org/';

    foreach ($eclipse_domains as $key => $value) {
      if (strpos($referer, $key)){
        $redirect = $value;
        break;
      }
    }

    // Destroy the session for the user.
    // Bug 443883 - [site_login] Password change should invalidate all active sessions
    if ($this->Session->isLoggedIn()) {
      $this->Session->destroy(TRUE);
      $this->messages['logout']['info'][] = 'You have been logged out.';
    }
    else{
      $this->messages['logout']['danger'][] = 'You are currently not logged in.';
      $redirect = 'https://dev.eclipse.org/site_login/';
    }

    return $redirect;
  }

  public function password_update() {
    $this->messages['logout']['success'][] = "Your account details have been updated successfully.";
    $this->messages['logout']['warning'][] = 'Please login to confirm your new password.';
  }

  public function showCountries() {
    $options = "";
    $continents = $this->_fetchcontinents();
    $countries = $this->_fetchCountries();

    foreach ($continents as $continent) {
      $options .= '<optgroup label="'. $continent .'">';
      foreach ($countries as $country) {
        if ($country['continent'] == $continent) {
          $selected = "";
          if (!empty($this->country) && $this->country == $country['ccode']) {
            $selected = "selected";
          }
          $options .= '<option value="'. $country['ccode'] .'" ' . $selected.'>'. $country['description'] .'</option>';
        }
      }
      $options .= '</optgroup>';
    }
    return $options;
  }

  function verifyUserStatus() {
    # bug 432822 - if someone is already logged in, send them to their account info page
    if (empty($this->takemeback)) {
      $this->takemeback = 'myaccount.php';
    }
    if ($this->Session->getGID() != "") {
      header("Location: " . $this->takemeback, 302);
      exit;
    }
  }

  /**
   * Validate takemeback Url
   *
   * Bug 421097
   * @return boolean
   */
  public function validateTakemebackUrl($takemeback = "") {
    if ($takemeback == "") {
      $takemeback = $this->takemeback;
    }

    $domains = array(
      'eclipse.org',
      'planeteclipse.org',
      'locationtech.org',
      'polarsys.org',
      'eclipse.local'
    );

    foreach ($domains as $d) {
      if (preg_match('#^(http(s)?:\/\/)(www\.)?([\w+0-9-]{0,}\.)?' . $d . '(:\d{1,5})?(\/)?#', $takemeback) &&
          strpos($takemeback, $d . ".") === FALSE){
        return TRUE;
        break;
      }
    }
    return FALSE;
  }

  private function _confirmAccount() {
    $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE token IN ('TOKEN_FAILED', 'CONFIRM_SUCCESS') AND  ip = " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']);
    $rs = $this->App->eclipse_sql($sql);
    $myrow = mysql_fetch_assoc($rs);
    if ($myrow['RecordCount'] > 0) {
      $this->messages['confirm']['danger'][] = "<b>You have already submitted a request. Please check your email inbox and spam folders to respond to the previous request.</b>  (8728s)";
    }
    else {
      if ($this->t != "") {
        $sql = "SELECT /* USE MASTER */ email, fname, password, lname, COUNT(1) AS RecordCount FROM account_requests WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
        $rs = $this->App->eclipse_sql($sql);
        $myrow = mysql_fetch_assoc($rs);
        if ($myrow['RecordCount'] <= 0) {
          $this->messages['confirm']['danger'][] = "We were unable to validate your request.  The supplied token is invalid; perhaps it has expired?  Please try creating your account again, and contact webmaster@eclipse.org if the problem persists. (8729s)";
          # If we can't find a record, insert a record preventing this dude from bombing us
          $this->t = $this->App->getAlphaCode(64);
          $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->t)) . ",
              '',
              'token_failed',
              'token_failed',
              'token_failed',
              " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
              NOW(),
              'TOKEN_FAILED')"
          );
          $EventLog = new EvtLog();
          $EventLog->setLogTable("__ldap");
          $EventLog->setPK1($this->App->sqlSanitize($this->t));
          $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
          $EventLog->setLogAction("ACCT_CREATE_TOKEN_FAILED");
          $EventLog->insertModLog("apache");
        }
        else {
          // New accounts will always have a value in $myrow['password'].
          $token_confirm = 'CONFIRM_SUCCESS';
          # Update this row, change IP address to reflect that of the person who successfully confirmed this email to avoid bombing
          $sql = "UPDATE account_requests SET token = ". $this->App->returnQuotedString($this->App->sqlSanitize($token_confirm)) .", ip = " . $this->App->returnQuotedString($this->App->sqlSanitize($_SERVER['REMOTE_ADDR']))
          . " WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
          $rs = $this->App->eclipse_sql($sql);

          $this->messages['confirm']['success'][] = "Thank you for confirming your email address.
          Your Eclipse.org account is now active and you may now </strong>log in</strong></a>.
          Please note that some Eclipse.org pages may require you to provide your login
          credentials.";

          $EventLog = new EvtLog();
          $EventLog->setLogTable("__ldap");
          $EventLog->setPK1($this->App->sqlSanitize($this->t));
          $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
          $EventLog->setLogAction("ACCT_CREATE_CONFIRM");
          $EventLog->insertModLog($myrow['email']);
        }
      }
      else {
        $this->messages['confirm']['danger'][] = "We were unable to validate your request.  The supplied token is invalid.  Please contact webmaster@eclipse.org.";
      }
    }
  }

  private function _createAccount() {
    if ($this->username != "" && $this->fname != "" && $this->lname != "" && $this->password1 != "") {
      if (!$this->FormToken->verifyToken($_POST['token-create-account']) || !empty($_POST['create-account-email-req'])) {
        # Send mail to webmaster
        $mail = "Dear webmaster,\n\n";
        $mail .= "A new eclipse.org account was denied:\n\n";
        $mail .= "Email: " . $this->username . "\n\n";
        $mail .= "First name: " . $this->fname . "\n\n";
        $mail .= "Last name: " . $this->lname . "\n\n";

        $mail .= "Organization: " . $this->organization. "\n\n";
        $mail .= "Country: " . $this->country. "\n\n";
        $mail .= "Remote addr: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
        $mail .= "Browser: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n";
        $mail .= "Referer: " . $_SERVER['HTTP_REFERER'] . "\n\n";

        $mail .= " -- Eclipse webdev\n";
        $headers = 'From: Eclipse Webmaster (automated) <webmaster@eclipse.org>' . "\n" . 'Content-Type: text/plain; charset=UTF-8';
        mail('webmaster@eclipse.org', "Denied Account: Possible spammer", $mail, $headers);
        return FALSE;
      }
      # Create an account.  Check to ensure this IP address hasn't flooded us with requests
      # or that this email address doesn't already have an account
      $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE ip = " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']);
      $rs = $this->App->eclipse_sql($sql);
      $myrow = mysql_fetch_assoc($rs);
      if ($myrow['RecordCount'] >= 25) {
        $this->messages['create']['danger'][] = "You have already submitted a request. Please check your email inbox and spam folders to respond to the previous request. (8723s)";
      }
      else {
        # Check LDAP
        if(!$this->Ldapconn->checkEmailAvailable($this->username)) {
          $this->messages['create']['danger'][] = "That account already exists.  If you cannot remember your password, please use the password reset option below.  (8725s)";
          # Jot this down to avoid repetitively polling ldap
          $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . ",
          '',
          " . $this->App->returnQuotedString($this->App->sqlSanitize($this->fname)) . ",
          " . $this->App->returnQuotedString($this->App->sqlSanitize($this->lname)) . ",
          '',
          " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
          NOW(),
          " . $this->App->returnQuotedString("CREATE_FAILED") . ")");

          $EventLog = new EvtLog();
          $EventLog->setLogTable("__ldap");
          $EventLog->setPK1($this->username);
          $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
          $EventLog->setLogAction("ACCT_CREATE_ALREADY_EXISTS");
          $EventLog->insertModLog("apache");
        }
        else {
          if ($this->agree != 1) {
            $this->messages['create']['danger'][] = "- You must agree to the terms and contitions of use<br />";
          }

          if (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username)) {
            $this->messages['create']['danger'][] = "- Your email address is not formatted correctly<br />";
          }

          if (!$this->Captcha->validate()) {
            $this->messages['create']['danger'][] = "- You haven't answered the captcha question correctly<br />";
          }
          if (!preg_match("/(?=^.{6,}$)(?=.*[\d|\W])(?=.*[A-Za-z]).*$/", $this->password1)) {
            $this->messages['create']['danger'][] = "- Your password does not meet the complexity requirements.  It must be at least 6 characters long, and contain one number or one symbol.<br />";
          }

          if (!$cryptopass = $this->_generateCryptotext($this->App->sqlSanitize($this->password1))) {
            $this->messages['create']['danger'][] = "- An error occurred while processing your request. (8730s)";
          }

          if (empty($this->country)) {
            $this->messages['create']['danger'][] = "- You must select your country of residence.";
          }

          if (empty($this->messages['create']['danger'])) {
            # Add request to database
            $this->t = $this->App->getAlphaCode(64);
            $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->username))) . ",
            '',
            " . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->fname))) . ",
            " . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->lname))) . ",
            '" . $cryptopass . "',
            " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
            NOW(),
            " . $this->App->returnQuotedString($this->t) . ")");


            $this->App->eclipse_sql("INSERT INTO users_profiles
                (user_uid,user_mail,user_country,user_org,user_status)
                VALUES (
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->t)) .",
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) .",
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->country)) .",
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->organization)) .",
                  0
                )"
            );

            if ($this->newsletter_status === 'subscribe') {
              $Subscriptions = $this->App->getSubscriptions();
              $Subscriptions->setFirstName($this->fname);
              $Subscriptions->setLastName($this->lname);
              $Subscriptions->setEmail($this->username);
              $Subscriptions->addUserToList();
            }

            $EventLog = new EvtLog();
            $EventLog->setLogTable("__ldap");
            $EventLog->setPK1($this->t);
            $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
            $EventLog->setLogAction("ACCT_CREATE_REQ_SUCCESS");
            $EventLog->insertModLog($this->username);

            # Send mail to dest
            $mail = "Dear $this->fname,\n\n";
            $mail .= "Thank you for registering for an account at Eclipse.org. Before we can activate your account one last step must be taken to complete your registration.\n\n";
            $mail .= "To complete your registration, please visit this URL:\nhttps://dev.eclipse.org/site_login/token.php?stage=confirm&t=$this->t\n\n";
            $mail .= "Your Username is: $this->username\n\n";
            $mail .= "If you have any problems signing up please contact webmaster@eclipse.org\n\n";
            $mail .= " -- Eclipse webmaster\n";
            $headers = 'From: Eclipse Webmaster (automated) <webmaster@eclipse.org>' . "\n" . 'Content-Type: text/plain; charset=UTF-8';
            mail($this->username, "Eclipse Account Registration", $mail, $headers);

            # Debug
            //print $mail;

            $this->messages['create']['success'][] =  "<p>Welcome to the Eclipse.org community!  We've sent a confirmation to the email address
            you have provided.  In that email there are instructions you must follow in order to activate your account.</p>
            <p>If you have not received the email within a few hours, and you've made sure it's not in your Junk, Spam or trash folders, please contact webmaster@eclipse.org</p>";
          }
          else {
            $this->messages['create']['danger'][] = "An error occurred while processing your request.  Please ensure that all the required fields are entered correctly and try again.  (5496s)";
          }
        }
      }
    }
    else {
      $this->messages['create']['danger'][] = "An error occurred while processing your request.  Please ensure that all the required fields are entered correctly and try again.  (8726s)";
    }
  }

  private function _generateBugzillaSHA256Password($_password) {
    $cp = 0;
    if ($_password != "") {
      # Generate random salt
      $hash = "{SHA-256}";
      $salt = $this->App->getAlphaCode(8);
      $cp = str_replace("=", "", $salt . base64_encode(hash("sha256", $_password . $salt, true))) . $hash;
    }
    return $cp;
  }

  private function _generateCryptotext($plaintext) {
    if (empty($plaintext) || !is_readable($this->path_public_key)) {
      return FALSE;
    }

    #load public key
    $fp = fopen($this->path_public_key, "r");
    $pub_key = fread($fp, 8192);
    fclose($fp);

    $key = openssl_pkey_get_public($pub_key);
    openssl_public_encrypt($plaintext, $cryptotext, $key, OPENSSL_PKCS1_OAEP_PADDING);

    #encode the output
    return base64_encode($cryptotext);
  }

  private function _generatePassword($_num_chars) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1023456789,.;:/@#$%^&*()-_=+";
    srand((double)microtime()*1000000);
    $loopcount   = 0;
    $rValue   = "";
    while (!preg_match("/(?=^.{6,}$)(?=.*\d)(?=.*[A-Za-z]).*$/", $rValue)) {
      $rValue   = "";
      $i = 0;
      $loopcount++;
      srand((double)microtime()*1000000);
      while ($i <= $_num_chars) {
        $num = rand() % strlen($chars);
        $rValue .= substr($chars, $num, 1);
        $i++;
      }
      # antilooper
      if($loopcount > 1000) {
        $rValue = "aA1$" . $this->App->getAlphaCode(4);
      }
    }
    return $rValue;
  }

  private function _getMessageContainer($message = '', $type = 'alert') {
    $class = "alert alert-" . $type;
    return '<div class="' . $class . '" role="alert">' . $message . '</div>';
  }

  private function _get_default_field_value($id, $value, $default_values = TRUE) {
    // If the value is not empty and the user is not requesting the default values,
    // return the updated values.
    if (!empty($value) && $default_values === FALSE) {
      return $value;
    }

    switch ($id) {
      case 'fname':
        return $this->Friend->getFirstName();
        break;

      case 'lname':
        return $this->Friend->getLastName();
        break;

      case 'username':
        return $this->Friend->getEmail();
        break;

      case 'githubid':
        return $this->Ldapconn->getGithubIDFromMail($this->Friend->getEmail());
        break;
    }
  }

  private function _get_profile_from_token($token = NULL){
    if (empty($token)) {
      return FALSE;
    }
    $sql = "SELECT /* USE MASTER */
        user_org as organization, user_jobtitle as jobtitle, user_bio as bio, user_interests as interests, user_website as website, user_twitter_handle as twitter_handle, user_country as country
      FROM users_profiles
      WHERE  user_uid = " . $this->App->returnQuotedString($token) . "
      ORDER BY user_update DESC LIMIT 1";
      $rs = $this->App->eclipse_sql($sql);
      $profile = mysql_fetch_assoc($rs);

    if (!empty($profile)) {
      foreach ($profile as $key => $value) {
        if (is_null($value)) {
          $value = "";
        }
        $this->{$key} = $value;
      }
      return TRUE;
    }
    return FALSE;
  }

  private function _get_default_profile_fields($get_default_values = FALSE){

    // Making sure we don't have an empty user_uid to avoid pre-populating
    // the account creation fields with an empty user_uid
    if (empty($this->user_uid)) {
      return FALSE;
    }

    if (empty($this->messages['profile']['danger'])) {
      $sql = "SELECT /* USE MASTER */
        user_org as organization, user_jobtitle as jobtitle, user_bio as bio, user_interests as interests, user_website as website, user_twitter_handle as twitter_handle, user_country as country
      FROM users_profiles
      WHERE  user_uid = " . $this->App->returnQuotedString($this->user_uid) . "
      ORDER BY user_update DESC LIMIT 1";
      $rs = $this->App->eclipse_sql($sql);
      $profile = mysql_fetch_assoc($rs);

      $this->profile_default = $profile;
      if ($get_default_values) {
        return TRUE;
      }

      if (!empty($profile)) {
        foreach ($profile as $key => $value) {
          if (is_null($value)) {
            $value = "";
          }
          $this->{$key} = $value;
        }
      }
    }
  }

  private function _getProfileDefaultValues(){
    if (empty($this->profile_default)) {
      $this->_get_default_profile_fields(TRUE);
    }
    return $this->profile_default;
  }

  private function _processSaveProfile() {
    if (!$this->FormToken->verifyToken($_POST['token-update-profile']) || !empty($_POST['profile-name-req'])) {
      //token verification failed or expected empty field wasn't empty
      return FALSE;
    }
    if ($this->password_expired === TRUE) {
      $this->messages['password_expired']['danger'][] = "You need to set a new password before you can update your profile.";
      return FALSE;
    }
    $fname = $this->_get_default_field_value('fname', $this->fname, FALSE);
    $lname = $this->_get_default_field_value('lname', $this->lname, FALSE);

    $default_values = $this->_getProfileDefaultValues();
    $default_org = $default_values['organization'];

    $fields = array(
      'user_uid' => $this->user_uid,
      'user_mail' => $this->user_mail,
      'user_org' => $this->organization,
      'user_jobtitle' => $this->jobtitle,
      'user_website' => $this->website,
      'user_bio' => $this->bio,
      'user_interests' => $this->interests,
      'user_twitter_handle' => $this->twitter_handle,
      'user_country' => $this->country,
    );

    $possible_null_field = array(
      'user_org',
      'user_jobtitle',
      'user_website',
      'user_bio',
      'user_interests',
      'user_twitter_handle',
    );

    # Validate values
    if (empty($fields['user_uid']) || !is_string($fields['user_uid'])) {
      $this->messages['profile']['danger'][] = 'Invalid user id<br>';
    }
    if (!empty($fields['user_website']) && !filter_var($fields['user_website'], FILTER_VALIDATE_URL)) {
      $this->messages['profile']['danger'][] = 'Invalid website URL<br>';
    }
    if (empty($fields['user_country']) && !in_array($fields['user_country'], $this->getCountryList())) {
      $this->messages['profile']['danger'][] = 'You must enter a valid country<br>';
    }

    if (!empty($this->messages['profile']['danger'])) {
      return FALSE;
    }

    //if they are a committer and have changed employers toss all changes and throw a warning + send a message
    if ($this->is_committer) {
      if ($default_org !== $fields["user_org"]) {
        if ($this->changed_employer === 'Yes') {
          // Send mail to dest
          $this->_sendNotice();
          $this->messages['myaccount']['danger'][] = "You have indicated a change in employer.  As such any changes you made have not been saved.  A notice has been sent to you and EMO legal (emo-records@eclipse.org) so that they can advise what paperwork(if any) needs to be updated.";
          //exit
          return FALSE;
        }
        else if ($this->changed_employer !== "No")  {
          $this->messages['myaccount']['danger'][] = "You must indicate if you have changed employers in order to save changes to your organization.";
          return FALSE;
        }
      } else {
        if ($this->changed_employer === 'Yes') {
          // Send mail to dest
          $this->_sendNotice();
          $this->messages['myaccount']['danger'][] = "A notice has been sent to you and EMO legal (emo-records@eclipse.org) so that they can advise what paperwork (if any) needs to be updated due to your change in employers.";
        }
      }
    }

    foreach ($possible_null_field as $value) {
      if (empty($fields[$value])) {
        $fields[$value] = NULL;
      }
    }

    $sql = "INSERT INTO users_profiles (";
    $columns = array();
    $values = array();
    foreach ($fields as $key => $value) {
      if (!empty($value)) {
        $columns[] = $key;
        $values[] = '"' . $this->App->sqlSanitize($value) . '"';
      }
      else if(in_array($key, $possible_null_field)) {
        $columns[] = $key;
        $values[] = 'NULL';
      }
    }
    $sql .= implode(',', $columns);
    $sql .= ') VALUES (';
    $sql .= implode(',', $values);
    $sql .= ")  ON DUPLICATE KEY UPDATE";
    foreach ($columns as $key => $value){
      $sql .= ' ' .$value . '=' . $values[$key] . ',';
    }
    $sql = rtrim($sql, ',');
    $this->App->eclipse_sql($sql);
    $this->messages['profile']['success'][] = 'Your profile have been updated successfully.';

  }

  private function _processSave() {
    if (!$this->FormToken->verifyToken($_POST['token-edit-account']) || !empty($_POST['edit-account-email-req'])) {
      //token verification failed or expected empty field wasn't empty
      return FALSE;
    }
    // Check IF the password is expired
    // AND if the user is NOT trying to change the password
    if ($this->password_expired === TRUE && (empty($this->password1) && empty($this->password2))) {
      $this->messages['password_expired']['danger'][] = "You need to set a new password before you can update your Account Settings.";
      $this->getVariables("welcomeback");
      return FALSE;
    }

    $user_is_changing_password = FALSE;
    if ($this->username != "" && $this->fname != "" && $this->lname != "" && $this->password != "") {
      # update account.
      # we must first bind to ldap to be able to change attributes
      $dn = $this->Ldapconn->authenticate($this->Friend->getEmail(), $this->password);
      if ($dn) {
        #work out what's changed
        $fname_changed = ($this->Ldapconn->getLDAPAttribute($dn, "givenName") !== $this->fname) ? TRUE : FALSE ;
        $lname_changed = ($this->Ldapconn->getLDAPAttribute($dn, "sn") !== $this->lname) ? TRUE : FALSE ;
        $email_changed = ($this->Ldapconn->getLDAPAttribute($dn, "mail") !== $this->username) ? TRUE : FALSE ;

        //if they are a committer and have changed employers toss all changes and throw a warning + send a message
        if ($this->is_committer && $this->changed_employer === 'Yes') {
          // Send mail to dest
          $this->_sendNotice();
          //notify the user
          if ( !$lname_changed && !$email_changed) {
            //I guess they just want us to know they've changed employers
            $this->messages['myaccount']['danger'][] = "A notice has been sent to you and EMO legal (emo-records@eclipse.org) so that they can advise what paperwork(if any) needs to be updated due to your change in employers.";
          }
          else {
            //they've changed something
            $this->messages['myaccount']['danger'][] = "You have indicated a change in employer.  As such any changes you made have not been saved.  A notice has been sent to you and EMO legal (emo-records@eclipse.org) so that they can advise what paperwork(if any) needs to be updated.";
          }
          //reset form data
          $this->getVariables("welcomeback");
          //return
          return;
        }

        $update_bz_name = FALSE;
        if ($fname_changed) {
          $this->Ldapconn->changeAttributeValue($dn, $this->password, "givenName", $this->fname);
          $this->Friend->setFirstName($this->fname);
          $update_bz_name = TRUE;
        }

        if ($lname_changed) {
          if ($this->changed_employer === 'No' || !$this->is_committer) {
            $this->Ldapconn->changeAttributeValue($dn, $this->password, "sn", $this->lname);
            $this->Friend->setLastName($this->lname);
            $update_bz_name = TRUE;
            $this->_sendNotice("surname", "to: " . $this->lname);
          } else if($this->is_committer && empty($this->changed_employer)) {
            $this->messages['myaccount']['danger'][] = "You must indicate if you have changed employers in order to save changes to your last name.";
            return;

          }
        }

        //if either the first or last name has changed the cn should be updated.
        if ($fname_changed || $lname_changed) {
          $this->Ldapconn->changeAttributeValue($dn, $this->password, "cn", $this->fname . " " . $this->lname);
          $update_bz_name = TRUE;
        }

        if ($update_bz_name) {
          $this->App->bugzilla_sql("SET NAMES 'utf8'");
          $sql = "UPDATE profiles SET realname='" . $this->App->sqlSanitize($this->fname . " " . $this->lname) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . " LIMIT 1";
          $this->App->bugzilla_sql($sql);
          $this->Session->updateSessionData($this->Friend);
        }

        # Update GitHub ID?
        if ($this->githubid != "") {
          $oldgithubid = $this->Ldapconn->getGithubIDFromMail($this->Friend->getEmail());

          # we can't change GH ID's automagically
          if ($oldgithubid != "") {
            $this->messages['myaccount']['danger'][] = "- Your GitHub ID cannot be changed from this form.  Please contact webmaster@eclipse.org to update your GitHub ID.<br />";
          }
          else {
            $this->Ldapconn->setGithubID($dn, $this->password, $this->githubid);
            $this->messages['myaccount']['success'][] = "Your github id was saved successfully.";
          }
        }

        # User is trying to update change is password
        if (!empty($this->password1) && !empty($this->password2)) {
	        if (!preg_match("/(?=^.{6,}$)(?=.*[\d|\W])(?=.*[A-Za-z]).*$/", $this->password1)) {
	          $this->messages['myaccount']['danger'][] = "- Your password does not meet the complexity requirements.  It must be at least 6 characters long, and contain one number or one symbol.<br />";
	        }
	        else {
	          if ($this->password != $this->password1) {
	            $user_is_changing_password = TRUE;
	            $this->Ldapconn->changePassword($dn, $this->password, $this->password1);
	            $bzpass = &$this->_generateBugzillaSHA256Password($this->password1);
	            $sql = "UPDATE profiles SET cryptpassword='" . $this->App->sqlSanitize($bzpass) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . " LIMIT 1";
	            $this->App->bugzilla_sql($sql);
	            $this->App->ipzilla_sql($sql);
	            $this->messages['myaccount']['success'][] = "Your password was updated successfully.";
	          }
	          // If the user is trying to update password with the current password
	          else{
	            $this->messages['myaccount']['danger'][] = "- Your new password must be different than your current password.";
	          }
	        }
        }

        # if email address has changed, we must update Bugzilla DB record too.
        $oldmail = $this->Ldapconn->getLDAPAttribute($dn, "mail");
        $mailmsg = "";
        if($email_changed) {
          #Not a committer or didn't change employers?
          if (!$this->is_committer || $this->changed_employer === 'No') {
            if (!$this->Ldapconn->checkEmailAvailable($this->username)) {
              $this->messages['myaccount']['danger'][] = "- Unable to change your email address<br />";
            }
            elseif (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username)) {
              $this->messages['myaccount']['danger'][] = "- Your email address is not formatted correctly<br />";
            }
            else {
              # Check that someone isn't piling on a bunch of requests for mail changes just to piss everyone off
              $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE ip = " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']);
              $sql .= "OR email = " . $this->App->returnQuotedString($oldmail);
              $rs = $this->App->eclipse_sql($sql);
              $myrow = mysql_fetch_assoc($rs);
              if ($myrow['RecordCount'] > 3) {
                $this->messages['myaccount']['danger'][] = "<b>You have already submitted a request. Please check your email inbox and spam folders to respond to the previous request.</b>";
              }
              else {
                # Toss in a request to change the email address
                $this->messages['myaccount']['success'][] = " Please check your Inbox for a confirmation email with instructions to complete the email address change.  Your email address will not be updated until the process is complete.";
                $this->t = $this->t = $this->App->getAlphaCode(64);
                $sql = "INSERT INTO account_requests (email,new_email,fname,lname,password,ip,req_when,token)VALUES (" . $this->App->returnQuotedString($oldmail) . ",
                " . $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . ",
                " . $this->App->returnQuotedString("MAILCHANGE") . ",
                " . $this->App->returnQuotedString("MAILCHANGE") . ",
                '',
                " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
                NOW(),
                " . $this->App->returnQuotedString($this->t) . ")";
                $this->App->eclipse_sql($sql);

                # Send mail to dest
                $mail = "You (or someone pretending to be you) has changed their Eclipse.org account email address to this one (" . $this->App->sqlSanitize($this->username) . ") from this IP address:\n";
                $mail .= "    " . $_SERVER['REMOTE_ADDR'] . "\n\n";
                $mail .= "To confirm this email change, please click the link below:\n";
                $mail .= "    https://dev.eclipse.org/site_login/token.php?stage=confirm&t=$this->t\n\n";
                $mail .= "If you have not issued this request, you can safely ignore it.\n\n";
                $mail .= " -- Eclipse webmaster\n";
                $headers = 'From: Eclipse Webmaster (automated) <webmaster@eclipse.org>';
                mail($this->username, "Eclipse Account Change", $mail, $headers);
                //notify EMO
                $this->_sendNotice("Email address","from: " . $oldmail . " to: " . $this->username );
              }
            }
          }  else if ($this->is_committer && $this->changed_employer === "") {
            $this->messages['myaccount']['danger'][] = "You must indicate if you have changed employers in order to save changes to your email address.";
            return;
          }
        }


        if (empty($this->messages['myaccount']['danger'])) {
          $this->messages['myaccount']['success'][] = "Your account details have been updated successfully." . $mailmsg . "";
          if ($user_is_changing_password) {
             header("Location: https://dev.eclipse.org/site_login/logout.php?password_update=1", 302);
          }
        }
      }
      else {
        $this->messages['myaccount']['danger'][] = "Your current password is incorrect.";
      }
    }
    else {
      $this->messages['myaccount']['danger'][] = "Please ensure that all the required fields are entered correctly and try again.";
    }
  }

  private function _resetPassword() {
    if (!$this->FormToken->verifyToken($_POST['token-password-recovery']) || !empty($_POST['recover-account-email-req'])) {
      //token verification failed or expected empty field wasn't empty
      return FALSE;
    }
    # reset stage 1.  We got an email address, create token and email to user
    # make sure someone isn't blasting us.  We disregard "token failed" since a common use-case
    # is to click the reset link after it has expired.
    $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE token <> 'TOKEN_FAILED' AND fname = 'RESET' AND lname = 'RESET' AND ip = " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']);
    $rs = $this->App->eclipse_sql($sql);
    $myrow = mysql_fetch_assoc($rs);
    if ($myrow['RecordCount'] >= 13) {
      $this->messages['reset']['danger'][] = "<b>We were unable to determine your identity after several attempts. Subsequent inquiries will be ignored for our protection.  Please try later, or contact webmaster@eclipse.org for support.</b>  (8727s)";
    }
    else {
      if (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username)) {
        $this->messages['reset']['danger'][] = "<b>Your email address is not formatted correctly.</b><br />";
      }
      elseif ($this->Ldapconn->checkEmailAvailable($this->username)) {
        $this->messages['reset']['danger'][] = "<b>We were unable to determine your identity with the information you've supplied.</b>  Perhaps you don't have an Eclipse.org account, or your account is under a different email address.(8x27s)";
      }
      else {
        # Check to see if we're trying to reset the password of a valid account.
        $this->t = $this->App->getAlphaCode(64);
        $this->App->eclipse_sql("INSERT IGNORE INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . ",
        '',
        " . $this->App->returnQuotedString("RESET") . ",
        " . $this->App->returnQuotedString("RESET") . ",
        '',
        " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
        NOW(),
        " . $this->App->returnQuotedString($this->t) . ")");

        # Send mail to dest
        $mail = "You (or someone pretending to be you) has requested a password reset from:\n";
        $mail .= "    " . $_SERVER['REMOTE_ADDR'] . "\n\n";
        $mail .= "To change your password, please visit this URL:\nhttps://dev.eclipse.org/site_login/token.php?p=p&t=$this->t\n\n";
        $mail .= "If you have not requested this change, you can safely let it expire.  If you have any problems signing in please contact webmaster@eclipse.org\n\n";
        $mail .= " -- Eclipse webmaster\n";
        $headers = 'From: Eclipse Webmaster (automated) <webmaster@eclipse.org>';
        mail($this->username, "Eclipse Account Password Reset", $mail, $headers);
        $this->messages['reset']['success'][] = '<strong>Password Recovery:</strong> A token has been emailed to you to allow
        you to reset your Eclipse.org password.  Please check your Trash and Junk/Spam
        folders if you do not see this email in your inbox.';

        # Debug
        //print $mail;

        $EventLog = new EvtLog();
        $EventLog->setLogTable("__ldap");
        $EventLog->setPK1($this->t);
        $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
        $EventLog->setLogAction("PASSWD_RESET_REQ");
        $EventLog->insertModLog($this->username);
      }
    }
  }

  private function _resetPassword2() {
    # reset stage 2.  We got an token back.  If we find a record, allow user to reset password, then proceed to stage3
    if($this->t != "") {
      $sql = "SELECT /* USE MASTER */ email, COUNT(1) AS RecordCount FROM account_requests WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
      $rs = $this->App->eclipse_sql($sql);
      $myrow = mysql_fetch_assoc($rs);
      if($myrow['RecordCount'] <= 0) {
        $this->exipred_pass_token = TRUE;
        $this->_setStage('reset');
        $this->messages['reset2']['danger'][] = "<b>The supplied reset token is invalid; perhaps it has expired?  Please wait 5 minutes and try to <a href='password_recovery.php'>reset your password again</a>.  If the problem persits, please contact webmaster@eclipse.org.</b> (8129rs)";
        # If we can't find a record, insert a record preventing this dude from bombing us
        $this->t = $this->App->getAlphaCode(64);
        $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->t)) . ",
            '',
            'token_failed',
            'token_failed',
            'token_failed',
            " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
            NOW(),
            'TOKEN_FAILED')"
        );
      }
      else {
        # display password reset page.
        $EventLog = new EvtLog();
        $EventLog->setLogTable("__ldap");
        $EventLog->setPK1($this->t);
        $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
        $EventLog->setLogAction("PASSWD_RESET_CONF");
        $EventLog->insertModLog($myrow['email']);
      }
    }
  }

  private function _resetPassword3() {
    if (!$this->FormToken->verifyToken($_POST['token-password-reset']) || !empty($_POST['reset-account-email-req'])) {
      //token verification failed or expected empty field wasn't empty
      return FALSE;
    }
      # reset stage 3.  We got a token back, and user is submitting a password.
    if ($this->t != "" && $this->password1 != "" ) {
      if ($this->password1 != $this->password2) {
        $this->messages['reset3']['danger'][] = "Submitted passwords don't match.";
        $this->_setStage('reset2');
        return FALSE;
      }

      if (!$this->Captcha->validate()) {
        $this->messages['reset3']['danger'][] = "- You haven't answered the captcha question correctly<br />";
        $this->_setStage('reset2');
        return FALSE;
      }

      $sql = "SELECT /* USE MASTER */ email, COUNT(1) AS RecordCount FROM account_requests WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
      $rs = $this->App->eclipse_sql($sql);
      $myrow = mysql_fetch_assoc($rs);
      if ($myrow['RecordCount'] <= 0) {
        $this->messages['reset3']['danger'][] = "We were unable to validate your request.  The supplied token is invalid; perhaps it has expired?  Please try to <a href='createaccount.php'>reset your password again</a>.  If the problem persits, please contact webmaster@eclipse.org. (8329rs)";
        $this->_setStage('reset2');
        # If we can't find a record, insert a record preventing this dude from bombing us
        $this->t = $this->App->getAlphaCode(64);
        $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->t)) . ",
            '',
            'token_failed',
            'token_failed',
            'token_failed',
            " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
            NOW(),
            'TOKEN_FAILED')"
        );
      }
      else {
        if (!preg_match("/(?=^.{6,}$)(?=.*\d)(?=.*[A-Za-z]).*$/", $this->password1)) {
          $this->messages['reset3']['danger'][] = "- Your password does not meet the complexity requirements<br />";
          $this->_setStage('reset2');
        }
        elseif ($cryptopass = $this->_generateCryptotext($this->App->sqlSanitize($this->password1))) {
          # Update this row, change IP address to reflect that of the person who successfully confirmed this password to avoid bombing
          $sql = "UPDATE account_requests SET token = 'PASSWORD_SUCCESS', password='" . $cryptopass . "', ip = " . $this->App->returnQuotedString($this->App->sqlSanitize($_SERVER['REMOTE_ADDR']))
          . " WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
          $rs = $this->App->eclipse_sql($sql);

          $bzpass = &$this->_generateBugzillaSHA256Password($this->password1);
          $sql = "UPDATE profiles SET cryptpassword='" . $this->App->sqlSanitize($bzpass) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($myrow['email'])) . " LIMIT 1";
          $this->App->bugzilla_sql($sql);
          $this->App->ipzilla_sql($sql);

          $this->messages['reset']['success'][] = '<strong>Password Recovery:</strong>  Your password was reset.  You may now <a href="/site_login/index.php">log in</a>.  Please note that some Eclipse.org sites, such as Bugzilla, Wiki or Forums, may ask you to login again with your new password.';

          $EventLog = new EvtLog();
          $EventLog->setLogTable("__ldap");
          $EventLog->setPK1($this->t);
          $EventLog->setPK2($_SERVER['REMOTE_ADDR']);
          $EventLog->setLogAction("PASSWD_RESET_SUCCESS");
          $EventLog->insertModLog($myrow['email']);
        }
        else {
          $this->messages['create']['danger'][] = "An error occurred while processing your request.  Please ensure that all the required fields are entered correctly and try again.  (3543s)";
        }
      }
    }
    else {
      $this->_setStage('reset2');
      $this->messages['reset3']['danger'][] = "Please enter a new password.";
      return FALSE;
    }
  }

  private function _sanitizeVariables() {
    $inputs = array(
      'agree',
      'githubid',
      'fname',
      'lname',
      'password',
      'p',
      'page',
      'password',
      'password1',
      'password2',
      'password_update',
      'remember',
      'stage',
      'submit',
      'takemeback',
      't',
      'username',
      'organization',
      'jobtitle',
      'website',
      'bio',
      'interests',
      'twitter_handle',
      'changed_employer',
      'country',
      'newsletter_status',
   );

    foreach ($inputs as $field) {
      $this->$field = $this->App->getHTTPParameter($field, "POST");

      if ($field == 'takemeback' || $field == 'website') {
        $this->$field = urldecode($this->$field);
      }

      if ($field == 'fname' || $field == 'lname') {
        $this->$field = preg_replace(SITELOGIN_NAME_REGEXP, '', $this->$field);
      }
      else if ($field == 't') {
        $this->$field = preg_replace("/[^a-zA-Z0-9]/", "", $this->t);
      }
      else {
        $this->$field = preg_replace($this->xss_patterns, '', $this->$field);
      }

      // Remove whitespace characters on the githubid field
      if ($field == 'githubid') {
        $this->$field = preg_replace("/\s+/", "", $this->$field);
      }

      # Magic quotes feature is removed from PHP 5.4 but just incase.
      if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $this->$field = stripslashes($this->$field);
      }
    }

    $this->username = trim($this->username);

    if (!is_numeric($this->remember)) {
      $this->remember = 0;
    }

    # Takemeback processing
    $this->referer = "";
    if (isset($_SERVER['HTTP_REFERER'])) {
      $this->referer = $_SERVER['HTTP_REFERER'];
    }

    # Coming from the Wiki?  Redirect to Special:Userlogin to finish processign
    if(preg_match('/^(http|https):\/\/(wiki|wikitest)\.eclipse\.org\//', $this->referer, $matches)) {
      $location = substr($this->referer, strlen($matches[0]));
      #strip 'extra' index data bug 308257
      $location = preg_replace("/index\.php\?title\=/","",$location);
      $this->takemeback = $matches[0] . "index.php?title=Special:Userlogin&action=submitlogin&type=login&returnto=" . $location ;
    }

    # Forum login process broken with bad redirect
    # Bug 430302
    if (preg_match('#^https?://.*eclipse.org/forums/index.php\?t=login#', $this->referer, $matches)) {
      $this->takemeback = "https://www.eclipse.org/forums/index.php/l/";
    }

    # Since we use a secure cookie, anything http should be sent back https.
    if (preg_match("#^http://(.*)#", $this->takemeback, $matches)) {
      $this->takemeback = "https://" . $matches[1];
    }

  if (preg_match('#^https?://dev.eclipse.org/#', $this->takemeback) && !preg_match('#^https?://dev.eclipse.org/site_login/myaccount.php#', $this->takemeback)){
      $this->takemeback = "";
    }
    if (!$this->validateTakemebackUrl()) {
      $this->takemeback = "";
    }
  }

  private function _setStage($stage){
    $possible_values = array(
      'login',
      'create',
      'save',
      'save-profile',
      'reset',
      'reset2',
      'reset3',
      'confirm',
    );
    if ($this->t != "" && $stage == "confirm") {
      $this->stage = 'confirm';
    }
    elseif ($this->exipred_pass_token) {
       $this->stage = "reset";
    }
    elseif ($this->t == "" && $this->p == "" && $stage == 'password-recovery' && !empty($this->username)) {
      $this->stage = "reset";
    }
    elseif ($this->t != "" && $this->p == "p" && $stage == 'password-recovery') {
      $this->stage = "reset2";
    }
    elseif ($this->t != "" && $stage == 'password-recovery') {
      $this->stage = "reset3";
    }
    elseif (in_array($stage, $possible_values)){
      $this->stage = $stage;
    }
  }

  private function _sendNotice($changed="", $details=""){
    if ($this->is_committer) {
      //do nothing if the changed state isn't yes or no.
      if ($this->changed_employer === 'Yes') {
        $mail = "Because you have changed employers, you must promptly provide the EMO(emo-records@eclipse.org) with your new employer information.\r\n";
        $mail .= "The EMO will determine what, if any, new legal agreements and/or employer consent forms are required for your committer account to remain active.\r\n\r\n";
        $mail .= " -- Eclipse webmaster\r\n";
        $headers = "From: Eclipse Webmaster (automated) <webmaster@eclipse.org>\r\n";
        $headers .= "CC: EMO-Records <emo-records@eclipse.org>";
        mail($this->user_mail, "Eclipse Account Change", $mail, $headers);
      } else if ($this->changed_employer === 'No') {
        if ($changed === "" || $details === "" ){
          $mail = "Committer: " . $this->user_uid . "\r\n";
          $mail .= "Has changed something, but details are incomplete. \r\n";
          $mail .= "What changed: " . $changed . " \r\n";
          $mail .= "Details: " . $details . "\r\n\r\n";
          $mail .= "Committer confirms they have NOT changed employers \r\n\r\n";
        } else {
          $mail = "Committer: " . $this->user_uid . "\r\n";
          $mail .= "Has changed their " . $changed . " " . $details . "\r\n\r\n";
          $mail .= "Committer confirms they have NOT changed employers \r\n\r\n";
        }
        $headers = "From: Eclipse Webmaster (automated) <webmaster@eclipse.org>";
        mail("emo-records@eclipse.org", "Eclipse Account Change", $mail, $headers);
      }
    }
  }

  public function _showChangedEmployer() {
    //show the changed employer buttons
    if ($this->is_committer) {
      echo <<<END
      <div class="form-group  clearfix has-feedback">
        <label class="col-sm-6 control-label">Have you changed employers<sup>[<a href="https://www.eclipse.org/legal/#CommitterAgreements" title="Why are we asking this?">?</a>]</sup><span class="required">*</span></label>
        <div class="col-sm-16">
          <input type="radio" name="changed_employer" value="Yes"> Yes
          <input type="radio" name="changed_employer" value="No"> No
        </div>
      </div>
END;
    }
  }

  private function _userAuthentification() {
    $process = FALSE;
    if ($this->FormToken->verifyToken($_POST['token-login']) && empty($_POST['login-username'])) {
      $process = TRUE;
    }

    if (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username) && $this->stage == "login") {
      $this->messages['login']['danger'][] = "Your email address does not appear to be valid.";
      $process = FALSE;
    }

    if ($process) {
      $dn = $this->Ldapconn->authenticate($this->username, $this->password);
      if ($dn) {
        # If you've logged in with your uid, we need to get the email.
        if (!preg_match("/@/", $this->username)) {
          $this->username = $this->Ldapconn->getLDAPAttribute($dn, "mail");
        }

        $this->Friend->getIsCommitter();

        # Look up BZ ID

        $sql = "SELECT /* USE MASTER */ userid FROM profiles where login_name = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->username));
        $rs = $this->App->bugzilla_sql($sql);

        if ($myrow = mysql_fetch_assoc($rs)) {

          $uid = $this->Ldapconn->getUIDFromMail($this->username);
          $this->Friend->selectFriend($this->Friend->selectFriendID("uid", $uid));
          $this->Friend->setBugzillaID($myrow['userid']);

        }
        else {
          # Try to log into Bugzilla using these credentials
          # This will create one
          # creating one is important, since not all our sites use LDAP auth, and some rely on BZ auth
          $AccountCreator = New AccountCreator();
          $AccountCreator->setUsername($this->username);
          $AccountCreator->setPassword($this->password);
          $AccountCreator->execute();

          # create/update Gerrit account
          # Bug 421319
          # sleep(1);  # not needed if we take the time to log into Gerrit
          $AccountCreator = New AccountCreator();
          $AccountCreator->setUrl('https://git.eclipse.org/r/login/q/status:open,n,z');
          $AccountCreator->setAccountType('gerrit');
          $AccountCreator->setUsername($this->username);
          $AccountCreator->setPassword($this->password);
          $http_code = $AccountCreator->execute();
          # TODO: verify that account was created (see bugzilla SQL below)

          # Get BZ ID now that an acct should be created
          $sql = "SELECT /* USE MASTER */ userid FROM profiles where login_name = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->username));
          $rs = $this->App->bugzilla_sql($sql);
          if ($myrow = mysql_fetch_assoc($rs)) {
            $uid = $this->Ldapconn->getUIDFromMail($this->username);
            $this->Friend->selectFriend($this->Friend->selectFriendID("uid", $uid));
            $this->Friend->setBugzillaID($myrow['userid']);
          }
          else {
            $EventLog = new EvtLog();
            $EventLog->setLogTable("bugs");
            $EventLog->setPK1($this->password);
            $EventLog->setPK2($sql);
            $EventLog->setLogAction("AUTH_BZID_NOT_FOUND");
            $EventLog->insertModLog($dn);
            $this->Friend->setBugzillaID(41806);  # Nobody.
          }
        }

        # Override loaded friends info with LDAP info
        $this->Friend->setFirstName($this->Ldapconn->getLDAPAttribute($dn, "givenName"));
        $this->Friend->setLastName($this->Ldapconn->getLDAPAttribute($dn, "sn"));
        $realname = $this->Friend->getFirstName() . " " . $this->Friend->getLastName();
        $this->Friend->setDn($dn);
        $this->Friend->setEMail($this->username);

        $this->Session->setIsPersistent($this->remember);
        $this->Session->setFriend($this->Friend);
        $this->Session->create();


        # Only temporarily, re-hash the password in Bugzilla so that other services can use it
        $bzpass = $this->_generateBugzillaSHA256Password($this->password);
        $this->App->bugzilla_sql("SET NAMES 'utf8'");
        $sql = "UPDATE profiles SET cryptpassword='" . $this->App->sqlSanitize($bzpass) . "', realname='" . $this->App->sqlSanitize($realname) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . " LIMIT 1";

        $this->App->bugzilla_sql($sql);

        # Begin: Bug 432830 - Remove the continue button in site_login
        if ($this->takemeback != "") {
          header("Location: " . $this->takemeback, 302);
        }
        else {
         header("Location: myaccount.php", 302);
        }
        exit();
        # END: Bug 432830 - Remove the continue button in site_login
      }
      else {
        $this->messages["login"]['danger'][] = "Authentication Failed. Please verify that your email address and password are correct.";
      }
    }
  }

  private function _verifyIfPasswordExpired() {

    // Check if the user is logged in
    if($this->Session->isLoggedIn()){
      // Get the Distinguished Name from UID
      $dn = $this->Ldapconn->getDNFromUID($this->user_uid);
      // Get shadowLastChange in seconds
      $lastChange = ($this->Ldapconn->getLDAPAttribute($dn, "shadowLastChange")) * 86400;
      // Get the number of days
      $shadowMax = $this->Ldapconn->getLDAPAttribute($dn, "shadowMax");
      // Set the expiry date
      $expiryDate = strtotime('+'.$shadowMax.' days', $lastChange);
      $expireSoon = strtotime('-30 days', $expiryDate);
      if ($this->Friend->getIsCommitter()) {
        $numberOfDays = round(($expiryDate - time()) / (3600*24));
        if ($expiryDate >= time() && time() > $expireSoon) {
          $days = $numberOfDays == 1 ? 'day' : 'days';
          $this->messages['password_expire_soon']['info'][] = 'Your password expires in <strong>' . $numberOfDays . ' '. $days .'.</strong>';
          return FALSE;
        }
        if ($expiryDate < time()) {
          $this->messages['password_expired']['danger'][] = "Your password is expired. <br>Please update it immediately.";
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * This function fetches all the countries and continents
   * @return array
   * */
  private function _fetchCountries() {
     $sql = "SELECT
             countries.ccode,
             countries.en_description as description,
             countries.continent_code,
             continents.en_description as continent
             FROM SYS_countries as countries
             LEFT JOIN SYS_continents as continents
             ON countries.continent_code = continents.continent_code";
     $result = $this->App->eclipse_sql($sql);

     $countries = array();
     while ($row = mysql_fetch_array($result)) {
        $countries[] = $row;
     }
     $this->country_list = $countries;
     return $countries;
  }

  /**
   * This function fetches all the continents from the SYS_continents table
   * @return array
   * */
  private function _fetchcontinents() {
    $sql = "SELECT en_description FROM SYS_continents ORDER BY sort_order DESC";
    $result = $this->App->eclipse_sql($sql);

    $continents = array();
    while ($row = mysql_fetch_array($result)) {
      $continents[] = $row['en_description'];
    }
    return $continents;
  }

}
