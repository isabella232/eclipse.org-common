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

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/session.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/users/accountCreator.class.php");
require_once('/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/evt_log.class.php");

define('SITELOGIN_EMAIL_REGEXP', '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/');

define('SITELOGIN_NAME_REGEXP', '/[^\p{L}\p{N}\-\.\' ]/u');

class Sitelogin {

  private $App = NULL;

  private $agree = "";

  private $bio = "";

  private $githubid = "";

  private $Friend = NULL;

  private $fname = "";

  private $exipred_pass_token = FALSE;

  private $interests = "";

  private $jobtitle = "";

  private $Ldapconn = NULL;

  private $lname = "";

  private $messages = array();

  private $organization = "";

  private $p = "";

  private $page = "";

  private $password = "";

  private $password1 = "";

  private $password2 = "";

  private $referer = "";

  private $remember = "";

  private $Session = NULL;

  private $skill = "";

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

  function Sitelogin($stage = NULL) {
    $this->xss_patterns = array(
      '/<script[^>]*?>.*?<\/script>/si',
      '/<[\/\!]*?[^<>]*?>/si',
      '/<style[^>]*?>.*?<\/style>/siU',
      '/<![\s\S]*?–[ \t\n\r]*>/'
    );

    global $App;
    $this->App = $App;
    $this->Session = $this->App->useSession();
    $this->Friend = $this->Session->getFriend();
    $this->Ldapconn = new LDAPConnection();

    $this->_sanitizeVariables();
    $this->user_uid = $this->Ldapconn->getUIDFromMail($this->Friend->getEmail());
    $this->user_mail = $this->Friend->getEmail();

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

  public function getStage(){
    return $this->stage;
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
            $value[0] = '<p>Your request could not be processed for the following reason(s):</p>';
            $value[0] .= '<p><strong>' . $org_value . '</strong></p>';
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
      'fname' => "",
      'lname' => "",
      'githubid' => "",
      'organization' => "",
      'jobtitle' => "",
      'website' => "",
      'bio' => "",
      'interests' => "",
      'twitter_handle' => "",
    );

    $this->_get_default_profile_fields();
    # Bug 428032 - Multiple XSS on site_login
    $username = filter_var($this->username, FILTER_SANITIZE_EMAIL);
    $fname = filter_var($this->fname, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $lname = filter_var($this->lname, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $takemeback = filter_var($this->takemeback, FILTER_SANITIZE_ENCODED);
    $remember = filter_var($this->remember, FILTER_SANITIZE_NUMBER_INT);
    $agree = filter_var($this->agree, FILTER_SANITIZE_NUMBER_INT);
    $githubid = filter_var($this->Ldapconn->getGithubIDFromMail($this->Friend->getEmail()), FILTER_SANITIZE_STRING);
    $organization = filter_var($this->organization, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $jobtitle = filter_var($this->jobtitle, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $website = filter_var($this->website, FILTER_SANITIZE_URL);
    $bio = filter_var($this->bio, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $interests = filter_var($this->interests, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $token = filter_var($this->t, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);
    $twitter_handle = filter_var($this->twitter_handle, FILTER_SANITIZE_STRING,FILTER_FLAG_ENCODE_AMP|FILTER_FLAG_ENCODE_HIGH|FILTER_FLAG_ENCODE_LOW);

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
          $return['agree'] =  $agree;
          $return['takemeback'] = $takemeback;
        }
        break;

      case 'reset':
          $return['token'] = $token;

        break;
    }
    return $return;
  }

  public function logout(){
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
    $this->Session->destroy();
    $this->messages['logout']['info'][] = 'You have been logged out.';
    return $redirect;
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
    );

    foreach ($domains as $d) {
      if (preg_match('#^https?://' . $d . '/#', $takemeback)
          || preg_match('#^https?://[\w+0-9-]{0,}\.' . $d . '/#', $takemeback)) {
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
      if($this->t != "") {
        $sql = "SELECT /* USE MASTER */ email, COUNT(1) AS RecordCount FROM account_requests WHERE token = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->t));
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
          # Update this row, change IP address to reflect that of the person who successfully confirmed this email to avoid bombing
          $sql = "UPDATE account_requests SET token = 'CONFIRM_SUCCESS', ip = " . $this->App->returnQuotedString($this->App->sqlSanitize($_SERVER['REMOTE_ADDR']))
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
          $EventLog->setLogAction("ACCT_CREATE_CONFIRM_SUCCESS");
          $EventLog->insertModLog($myrow['email']);
        }
      }
      else {
        $this->messages['confirm']['danger'][] = "We were unable to validate your request.  The supplied token is invalid.  Please contact webmaster@eclipse.org.";
      }
    }
  }

  private function _createAccount(){
    if ($this->username != "" && $this->fname != "" && $this->lname != "" && $this->password1 != "") {
      # Create an account.  Check to ensure this IP address hasn't flooded us with requests
      # or that this email address doesn't already have an account
      $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE ip = " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']);
      $rs = $this->App->eclipse_sql($sql);
      $myrow = mysql_fetch_assoc($rs);
      if ($myrow['RecordCount'] >= 25) {
        $this->messages['create']['danger'][] = "You have already submitted a request. Please check your email inbox and spam folders to respond to the previous request. (8723s)";
      }
      else {
        // Verify if the user already submitted a request with this e-mail address.
        $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount FROM account_requests WHERE
        email = " . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->username)));
        $result = $this->App->eclipse_sql($sql);
        $row = mysql_fetch_assoc($result);
        if ($row['RecordCount'] != 0) {
          $this->messages['create']['danger'][] = "You have already submitted a request. Please check your email inbox and spam folders to respond to the previous request. (8724s)";
        }
        elseif (!$this->Ldapconn->checkEmailAvailable($this->username)) {
          # Check LDAP
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

          if ($this->skill != 16) {
            $this->messages['create']['danger'][] = "- You haven't answered the mathematical question correctly<br />";
          }
          if (!preg_match("/(?=^.{6,}$)(?=.*[\d|\W])(?=.*[A-Za-z]).*$/", $this->password1)) {
            $this->messages['create']['danger'][] = "- Your password does not meet the complexity requirements.  It must be at least 6 characters long, and contain one number or one symbol.<br />";
          }

          if (empty($this->messages['create']['danger'])) {
            # Add request to database
            $this->t = $this->App->getAlphaCode(64);
            mysql_set_charset('utf8');
            $this->App->eclipse_sql("INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->username))) . ",
            '',
            " . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->fname))) . ",
            " . $this->App->returnQuotedString($this->App->sqlSanitize(trim($this->lname))) . ",
            '" . $this->App->sqlSanitize($this->password1) . "',
            " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
            NOW(),
            " . $this->App->returnQuotedString($this->t) . ")");

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

            $this->messages['create']['success'][] =  "<p>Welcome to the Eclipse.org community!  Your account has been created successfully, and we've sent a confirmation to the email address
            you have provided.  In that email there are instructions you must follow in order to activate your account.</p>
            <p>If you have not received the email within a few hours, and you've made sure it's not in your Junk, Spam or trash folders, please contact webmaster@eclipse.org</p>";
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

  private function _get_default_field_value($id, $value) {
    if (!empty($value)) {
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

  private function _get_default_profile_fields(){
    if (empty($this->messages['profile']['danger'])) {
      $sql = "SELECT /* USE MASTER */
      user_org as organization, user_jobtitle as jobtitle, user_bio as bio,  user_interests as interests, user_website as website, user_twitter_handle as twitter_handle
      FROM users_profiles
      WHERE  user_uid = " . $this->App->returnQuotedString($this->user_uid) . "
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
      }
    }
  }

  private function _processSaveProfile() {
    $fname = $this->_get_default_field_value('fname', $this->fname);
    $lname = $this->_get_default_field_value('lname', $this->lname);

    $fields = array(
      'user_uid' => $this->user_uid,
      'user_mail' => $this->user_mail,
      'user_org' => $this->organization,
      'user_jobtitle' => $this->jobtitle,
      'user_website' => $this->website,
      'user_bio' => $this->bio,
      'user_interests' => $this->interests,
      'user_twitter_handle' => $this->twitter_handle,
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
    if (!empty($fields['user_website']) && !filter_var($fields['user_website'], FILTER_VALIDATE_URL)) {
      $this->messages['profile']['danger'][] = 'Invalid website URL<br>';
    }


    if (!empty($this->messages['profile']['danger'])) {
      return FALSE;
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

    if ($this->username != "" && $this->fname != "" && $this->lname != "" && $this->password != "") {
      # update account.
      # we must first bind to ldap to be able to change attributes
      $dn = $this->Ldapconn->authenticate($this->Friend->getEmail(), $this->password);
      if ($dn) {

        $update_bz_name = FALSE;
        if ($this->Ldapconn->getLDAPAttribute($dn, "givenName") != $this->fname) {
          $this->Ldapconn->changeAttributeValue($dn, $this->password, "givenName", $this->fname);
          $this->Friend->setFirstName($this->fname);
          $update_bz_name = TRUE;
        }

        if ($this->Ldapconn->getLDAPAttribute($dn, "sn") != $this->lname) {
          $this->Ldapconn->changeAttributeValue($dn, $this->password, "sn", $this->lname);
          $this->Friend->setLastName($this->lname);
          $update_bz_name = TRUE;
        }

        if ($this->Ldapconn->getLDAPAttribute($dn, "cn") != $this->fname . " " . $this->lname) {
          $this->Ldapconn->changeAttributeValue($dn, $this->password, "cn", $this->fname . " " . $this->lname);
          $update_bz_name = TRUE;
        }

        if ($update_bz_name) {
          $this->App->bugzilla_sql("SET NAMES 'utf8'");
          $sql = "UPDATE profiles SET realname='" . $this->App->sqlSanitize($this->fname . " " . $this->lname) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . " LIMIT 1";
          $this->App->bugzilla_sql($sql);
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
	            $this->Ldapconn->changePassword($dn, $this->password, $this->password1);
	            $bzpass = &$this->_generateBugzillaSHA256Password($this->password1);
	            $sql = "UPDATE profiles SET cryptpassword='" . $this->App->sqlSanitize($bzpass) . "' WHERE login_name = " .  $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . " LIMIT 1";
	            $this->App->bugzilla_sql($sql);
	            $this->App->ipzilla_sql($sql);
	            $this->messages['myaccount']['success'][] = "Your password was updated successfully.";
	          }
	        }
        }

        # if email address has changed, we must update Bugzilla DB record too.
        $oldmail = $this->Ldapconn->getLDAPAttribute($dn, "mail");
        $mailmsg = "";
        if($this->username != $oldmail) {
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
              $sql = "INSERT INTO account_requests VALUES (" . $this->App->returnQuotedString($oldmail) . ",
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
            }
          }
        }

        if (empty($this->messages['myaccount']['danger'])) {
          $this->messages['myaccount']['success'][] = "Your account details have been updated successfully." . $mailmsg . "";
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
      # Check to see if we're trying to reset the password of a valid account.
      $this->t = $this->App->getAlphaCode(64);
      $this->App->eclipse_sql("INSERT IGNORE INTO account_requests VALUES (" . $this->App->returnQuotedString($this->App->sqlSanitize($this->username)) . ",
      '',
      " . $this->App->returnQuotedString("RESET") . ",
      " . $this->App->returnQuotedString("RESET") . ",
      '" . $this->App->sqlSanitize($this->password1) . "',
      " . $this->App->returnQuotedString($_SERVER['REMOTE_ADDR']) . ",
      NOW(),
      " . $this->App->returnQuotedString($this->t) . ")");

      if (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username)) {
        $this->messages['reset']['danger'][] = "<b>Your email address is not formatted correctly.</b><br />";
      }
      elseif ($this->Ldapconn->checkEmailAvailable($this->username)) {
        $this->messages['reset']['danger'][] = "<b>We were unable to determine your identity with the information you've supplied.</b>  Perhaps you don't have an Eclipse.org account, or your account is under a different email address.(8x27s)";
      }
      else {
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
      # reset stage 3.  We got a token back, and user is submitting a password.
    if ($this->t != "" && $this->password1 != "" ) {
      if ($this->password1 != $this->password2) {
        $this->messages['reset3']['danger'][] = "Submitted passwords don't match.";
        $this->_setStage('reset2');
        return FALSE;
      }
      if ($this->skill != '16') {
        $this->messages['reset3']['danger'][] = "Skill question is wrong.";
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
        else {
          # Update this row, change IP address to reflect that of the person who successfully confirmed this password to avoid bombing
          $sql = "UPDATE account_requests SET token = 'PASSWORD_SUCCESS', password='" . $this->App->sqlSanitize($this->password1) . "', ip = " . $this->App->returnQuotedString($this->App->sqlSanitize($_SERVER['REMOTE_ADDR']))
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
      'remember',
      'skill',
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

    if (preg_match('#^https?://dev.eclipse.org/#', $this->takemeback) || !$this->validateTakemebackUrl()) {
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

  private function _userAuthentification() {
    if (!preg_match(SITELOGIN_EMAIL_REGEXP, $this->username) && $this->stage == "login") {
      $this->messages['login']['error'][] = "Your email address does not appear to be valid.";
    }

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
