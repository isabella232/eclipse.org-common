<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");

class Cla {

  private $App = NULL;

  private $cla_document_id = "";

  private $cla_expiry_date = "";

  private $cla_fields = array();

  private $cla_form_content = array();

  private $cla_is_signed = NULL;

  private $form = "";

  private $Friend = NULL;

  private $is_committer = "";

  private $messages = array();

  private $Session = NULL;

  private $SiteLogin = NULL;

  private $state = "";

  private $uid = "";

  public function Cla(App $App) {
    $this->App = $App;
    $this->Session = $this->App->useSession();
    $this->Friend = $this->Session->getFriend();
    $this->uid = $this->Friend->getUID();
    $this->is_committer = $this->Friend->getIsCommitter();

    // Get the current state
    $this->state = filter_var($this->App->getHTTPParameter("state", "POST"), FILTER_SANITIZE_STRING);
    $this->form = filter_var($this->App->getHTTPParameter("form_name", "POST"), FILTER_SANITIZE_STRING);
    if (!empty($this->uid) && $this->form == "cla-form") {
      switch ($this->state) {
        case 'submit_cla':
          $this->_submitClaDocument();
          break;
        case 'invalidate_cla':
          $this->_invalidateClaDocument();
          break;
        case 'disable_unsigned_notification':
          $this->_disableUnsignedNotification();
          break;
      }
    }

    // Check if the current user has a signed CLA
    if ($this->_claIsSigned() === FALSE && isset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION']) && $_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION'] === '1') {
      $this->_notifyUserOfUnsignedCla();
    }
  }

  /**
   * This function returns the CLA expiry date
   *
   * @return string
   * */
  public function getClaExpiryDate() {
    return $this->cla_expiry_date;
  }

  /**
   * These functions returns the text to put on the CLA form
   * @param $key - String containing a specified key
   * @return string
   * */
  public function getClaFormContent($key = "") {
    if (!empty($key) && isset($this->cla_form_content[$key])) {
      return $this->cla_form_content[$key];
    }
    return '';
  }

  /**
   * This function sets the CLA fields values from what's being posted from the form
   * */
  public function getFieldValues($field = "") {
    $this->cla_fields = array(
      'Question 1' => filter_var($this->App->getHTTPParameter("question_1", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'Question 2' => filter_var($this->App->getHTTPParameter("question_2", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'Question 3' => filter_var($this->App->getHTTPParameter("question_3", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'Question 4' => filter_var($this->App->getHTTPParameter("question_4", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'Email' => filter_var($this->App->getHTTPParameter("email", "POST"), FILTER_SANITIZE_EMAIL),
      'Legal Name' => filter_var($this->App->getHTTPParameter("legal_name", "POST"), FILTER_SANITIZE_STRING),
      'Public Name' => filter_var($this->App->getHTTPParameter("public_name", "POST"), FILTER_SANITIZE_STRING),
      'Employer' => filter_var($this->App->getHTTPParameter("employer", "POST"), FILTER_SANITIZE_STRING),
      'Address' => filter_var($this->App->getHTTPParameter("address", "POST"), FILTER_SANITIZE_STRING),
      'Agree' => filter_var($this->App->getHTTPParameter("cla_agree", "POST"), FILTER_SANITIZE_STRING)
    );

    // Return the field if we're asking for one in particular
    if (!empty($field) && !empty($this->cla_fields[$field])) {
      return $this->cla_fields[$field];
    }
  }

  public function getClaIsSigned() {
    if (is_null($this->cla_is_signed)) {
      $this->cla_is_signed = $this->_claIsSigned();
    }
    return $this->cla_is_signed;
  }

  /**
   * This function returns an Array containing the user's signed CLA
   *
   * @return array OR string
   * */
  public function getSignedClaDocument() {
    if (!empty($this->uid)) {
      $sql ="SELECT ScannedDocumentBLOB From PeopleDocuments
             WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->uid))."
             AND ExpirationDate IS NULL";
      $result = $this->App->foundation_sql($sql);

      if ($row = mysql_fetch_assoc($result)) {
        $decode = json_decode($row['ScannedDocumentBLOB'], TRUE);
        $decode['cla_doc'] = base64_decode($decode['cla_doc']);
        return $decode;
      }
    }
    return "Document not signed.";
  }

  /**
   * This function puts the right content on the CLA tab
   * */
  public function outputPage() {
    switch ($this->_claIsSigned()){
      case TRUE:
        include $_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/users/tpl/cla_record.tpl.php";
        break;
      case FALSE:
        $this->_claFormContent();
        include $_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/users/tpl/cla_form.tpl.php";
        break;
    }
  }

  /**
   * This function insert rows in the account_requests and SYS_EvtLog tables
   * depending on $action is specified
   *
   * @param $action - Validate or invalidate a CLA
   */
  private function _actionLdapGroupRecord($action) {
    $email = $this->Friend->getEmail();
    $accepted_actions = array(
        'CLA_SIGNED',
        'CLA_INVALIDATED'
    );
    if ($this->uid && in_array($action, $accepted_actions) && !empty($email)) {
      //Insert the request to add to LDAP.
      $sql = "INSERT INTO account_requests
              (email,fname,lname,password,ip,token,req_when)
              values (
                ".$this->App->returnQuotedString($this->App->sqlSanitize($email)).",
                ".$this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getFirstName())).",
                ".$this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getLastName())).",
                'eclipsecla',
                ".$this->App->returnQuotedString($this->App->sqlSanitize($_SERVER['REMOTE_ADDR'])).",
                ".$this->App->returnQuotedString($this->App->sqlSanitize($action)).",
                NOW()
              )";
      $result = $this->App->eclipse_sql($sql);

      // Log that this event occurred
      $sql = "INSERT INTO SYS_EvtLog
              (LogTable,PK1,PK2,LogAction,uid,EvtDateTime)
              values (
                'cla',
                ".$this->App->returnQuotedString($this->App->sqlSanitize($this->uid)).",
                'EclipseCLA-v1',
                ".$this->App->returnQuotedString($this->App->sqlSanitize($action)).",
                'cla_service',
                NOW()
              )";
      $result = $this->App->eclipse_sql($sql);
    }
    else {
      $this->App->setSystemMessage('account_requests', "There's been an error updated the LDAP group record. (LDAP-01)", "danger");
    }
  }

  /**
   * This function check if the current user has access to sign the CLA
   * @return BOOL
   * */
  private function _allowSigning() {

    // If user is logged in
    $email = $this->Friend->getEmail();
    if (!empty($this->uid) || !empty($email) || $this->Friend->checkUserIsFoundationStaff()) {
      return TRUE;
    }

    // The user is not logged in and is not part of the foundation staff
    return FALSE;
  }

  /**
   * This internal function prepares a data array and converts it to JSON,
   * it is a helper function for contributor_agreement__insert_cla_document
   *
   * @return string JSON encoded string.
   */
  private function _claDocumentInJson() {

    $cla_document = fopen('http://www.eclipse.org/legal/CLA.html', 'r');
    $data = array(
      'legal_name' => $this->cla_fields['Legal Name'],
      'public_name' => $this->cla_fields['Public Name'],
      'employer' => $this->cla_fields['Employer'],
      'address' => $this->cla_fields['Address'],
      'email' => $this->cla_fields['Email'],
      'question_1' => $this->cla_fields['Question 1'],
      'question_2' => $this->cla_fields['Question 2'],
      'question_3' => $this->cla_fields['Question 3'],
      'question_4' => $this->cla_fields['Question 4'],
      'agree' => $this->cla_fields['Agree'],
      'cla_doc' => base64_encode(stream_get_contents($cla_document)),
    );
    fclose($cla_document);
    return json_encode($data);
  }

  /**
   * This function fetches content from the CLA html file
   * */
  private function _claFormContent() {

    $cla_document = new DomDocument();
    $cla_document->loadhtmlfile('http://www.eclipse.org/legal/CLA.html');

    // Remove the #reference DIV
    $reference = $cla_document->getElementById('reference');
    $reference->parentNode->removeChild($reference);

    // Fetching the pieces of content by ID
    $question1 = $cla_document->getElementById('question1');
    $question2 = $cla_document->getElementById('question2');
    $question3 = $cla_document->getElementById('question3');
    $question4 = $cla_document->getElementById('question4');
    $text1 = $cla_document->getElementById('text1');
    $text2 = $cla_document->getElementById('text2');
    $text3 = $cla_document->getElementById('text3');

    $this->cla_form_content = array(
      'question_1' => $question1->nodeValue,
      'question_2' => $question2->nodeValue,
      'question_3' => $question3->nodeValue,
      'question_4' => $question4->nodeValue,
      'text_1' => $cla_document->saveXML($text1),
      'text_2' => $cla_document->saveXML($text2),
      'text_3' => $cla_document->saveXML($text3),
    );
  }

  /**
   * Ckeck if Effective Date the CLA was signed for the logged in user,
   * or FALSE if no record was found
   *
   * @return string or BOOL FALSE
   */
  private function _claIsSigned() {
    $cla_document_id = $this->_getClaDocumentId();
    if (!empty($this->uid) && !empty($cla_document_id)) {
      $sql = "SELECT EffectiveDate FROM PeopleDocuments
              WHERE PersonID = ". $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) ."
              AND DocumentID = ". $this->App->returnQuotedString($this->App->sqlSanitize($cla_document_id)) ."
              AND ExpirationDate IS NULL";
      $result = $this->App->foundation_sql($sql);

      if ($row = mysql_fetch_assoc($result)) {
        // Returns the Expiry date and making sure we remove the time from the date.
        $this->cla_expiry_date = date("Y-m-d", strtotime('+3 years', strtotime($row['EffectiveDate'])));
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * This function creates a new people record in the foundationDB
   * if it can't find an existing one
   *
   * @return bool
   */
  private function _createPeopleRecordIfNecessary() {

    if (!isset($this->uid) || empty($this->uid)) {
      return FALSE;
    }

    $sql = "SELECT PersonID FROM People
        WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->uid));
    $result = $this->App->foundation_sql($sql);

    if ($row = mysql_fetch_assoc($result)) {
      if (isset($row['PersonID']) && !empty($row['PersonID'])) {
        $found_uid = TRUE;
        return TRUE;
      }
    }

    if (!isset($found_uid)) {
      $sql = "INSERT INTO People
              (PersonID, FName, LName, Type, IsMember, Email, IsUnixAcctCreated)
              values (
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) .",
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getFirstName())) .",
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getLastName())) .",
                'XX',
                0,
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getEmail())) .",
                0
              )";
      $result = $this->App->foundation_sql($sql);

      // Log that this event occurred
      $sql = "INSERT INTO SYS_ModLog
                (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
                VALUES (
                  'cla',
                  'cla_service',
                  'EclipseCLA-v1',
                  'NEW PEOPLE RECORD',
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) .",
                  NOW()
                )";
      $result = $this->App->foundation_sql($sql);

      return TRUE;
    }
    return FALSE;
  }

  /**
   * This function sets a cookie to hide the unsigned notification message
   * */
  private function _disableUnsignedNotification() {
    setcookie ('ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION', '1',  time() + 3600 * 24 * 1095, '/' );
  }

  /**
   * This internal function returns the Document ID for CLAs
   * @return string OR NULL
   * */
  private function _getClaDocumentId() {
    $sql = "SELECT DocumentId FROM SYS_Documents
            WHERE Description='Contributor License Agreement'
            AND Version=1 AND Type='IN'";
    $result = $this->App->foundation_sql($sql);
    if ($row = mysql_fetch_assoc($result)) {
      return $row['DocumentId'];
    }
    return NULL;
  }

/**
 * This function invalidates a user's CLA document
 */
  private function _invalidateClaDocument() {
    if (!empty($this->uid) && $this->_getClaDocumentId()) {
      //First we need to find the active CLA record.
      $sql = "SELECT PersonID, EffectiveDate
                FROM PeopleDocuments
                WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) . "
                AND DocumentID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->_getClaDocumentId())) . "
                AND ExpirationDate IS NULL";
      $result = $this->App->foundation_sql($sql);

      if ($myrow = mysql_fetch_assoc($result)) {
         // Log that this event occurred Note that foundationdb uses SYS_ModLog instead of SYS_EvtLog;
        $sql = "INSERT INTO SYS_ModLog
                  (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
                  values (
                    'cla',
                    'cla_service',
                    'EclipseCLA-v1',
                    'INVALIDATE_CLA DOCUMENT',
                    ".$this->App->returnQuotedString($this->App->sqlSanitize($myrow['PersonID'])).",
                    NOW()
                  )";
        $result = $this->App->foundation_sql($sql);

        $sql = "UPDATE PeopleDocuments
                SET ExpirationDate=NOW()
                WHERE PersonID = ".$this->App->returnQuotedString($this->App->sqlSanitize($myrow['PersonID']))."
                AND DocumentID = ".$this->App->returnQuotedString($this->App->sqlSanitize($this->_getClaDocumentId()))."
                AND EffectiveDate = ".$this->App->returnQuotedString($this->App->sqlSanitize($myrow['EffectiveDate']));
        $result = $this->App->foundation_sql($sql);

        //Invalidate the users LDAP group.
        $this->_actionLdapGroupRecord('CLA_INVALIDATED');

        // Making sure we add the notification back in the page
        if (isset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION'])) {
          unset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION']);
          setcookie('ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION', '', time() - 3600, '/');
        }


        // Create success message
        $this->App->setSystemMessage('invalidate_cla','You have successfuly invalidated your CLA.','success');
      }
      else {
        // Create error message
        $this->App->setSystemMessage('invalidate_cla','An attempt to invalidate the CLA failed because we were unable to find the CLA that matches. (LDAP-02)','danger');
      }
    }
  }

  /**
   * This function let the user know about an unsigned CLA
   * */
  private function _notifyUserOfUnsignedCla() {

    // Check if user don't want to see the notification
    if (isset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION']) && $_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION'] === '1') {
      return FALSE;
    }

    $committer_string = '';
    if ($this->is_committer) {
      $committer_string = ' for which you are not a committer ';
    }

    $message = '
      <p>In order to contribute code to an Eclipse Foundation Project ' . $committer_string . 'you will be required to sign a Contributor License Agreement (CLA).</p>
      <form action="" method="POST">
        <input type="hidden" name="unsigned_cla_notification" value="1">
        <input type="hidden" name="state" value="disable_unsigned_notification">
        <ul class="list-inline margin-top-10 margin-bottom-0">
          <li><a class="small btn btn-primary" href="http://www.eclipse.org/legal/clafaq.php">What is a CLA?</a></li>
          <li><a class="small btn btn-primary" href="#open_tab_cla">Sign your CLA</a></li>
          <li><button class="small btn btn-primary">Disable this message</button></li>
        </ul>
      </form>';

    $this->App->setSystemMessage('unsigned_cla',$message,'info');
  }

  /**
   * This internal function inserts a new CLA document based off the form data submitted.
   */
  private function _submitClaDocument() {

    // Get values from the submitted form
    $this->getFieldValues();

    // Check if the sumitted fields validate and if there is no signed CLA for this user
    if ($this->_allowSigning() && $this->_validatedClaFields() && !$this->_claIsSigned() && $this->_getClaDocumentId()) {

      $this->_createPeopleRecordIfNecessary();

      // get the CLA document in Json format
      $blob = $this->_claDocumentInJson();

      $sql = "INSERT INTO PeopleDocuments
                (PersonId,DocumentId,Version,EffectiveDate,ReceivedDate,
                ScannedDocumentBLOB,ScannedDocumentMime,ScannedDocumentBytes,
                ScannedDocumentFileName,Comments)
              VALUES (
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) .",
                ". $this->App->returnQuotedString($this->App->sqlSanitize($this->_getClaDocumentId())) .",
                1,
                now(),
                now(),
                '". $blob ."',
                'application/json',
                ". strlen($blob) .",
                'eclipse-cla.json',
                'Automatically generated CLA'
              )";
      $result = $this->App->foundation_sql($sql);

      // Log that this event occurred
      $sql = "INSERT INTO SYS_ModLog
                (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
                VALUES (
                  'cla',
                  ". $this->App->returnQuotedString($this->App->sqlSanitize($this->uid)) .",
                  'EclipseCLA-v1',
                  'NEW CLA DOCUMENT',
                  'cla_service',
                  NOW()
                )";
      $result = $this->App->foundation_sql($sql);

      // Submit the users LDAP group.
      $this->_actionLdapGroupRecord('CLA_SIGNED');

      $this->App->setSystemMessage('submit_cla',"You successfully submitted the CLA!",'success');
    }
    else {
      $this->App->setSystemMessage('submit_cla',"Error, the CLA have not been submitted. (LDAP-03)",'danger');
    }
  }

  /**
   * This function checks if all the fields from the form validates
   *
   * @return BOOL
   * */
  private function _validatedClaFields() {
    $is_valid = TRUE;
    foreach ($this->cla_fields as $field_name => $field_value) {
      if (strpos($field_name, 'Question') !== FALSE && $field_value !== "1") {
        $this->App->setSystemMessage('submit_cla','You must accept ' . $field_name,'danger');
        $is_valid = FALSE;
      }
      if (($field_name == 'Email' || $field_name == 'Legal Name' || $field_name == 'Employer' || $field_name == 'Address') && empty($field_value)) {
        $this->App->setSystemMessage('submit_cla','You must enter your ' . $field_name,'danger');
        $is_valid = FALSE;
      }
      if ($field_name == 'Agree' && $field_value !== 'I AGREE') {
        $this->App->setSystemMessage('submit_cla','You must enter "I AGREE" in the Electronic Signature field.','danger');
        $is_valid = FALSE;
      }
    }
    return $is_valid;
  }

}