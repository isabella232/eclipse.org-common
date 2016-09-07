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

require_once(realpath(dirname(__FILE__) . "/../friends/friend.class.php"));

class Cla {

  /**
   * Eclipse App class
   *
   * @var stdClass
   */
  private $App = NULL;

  /**
   * List of possible contributor agreements
   *
   * @var Array
   */
  private $contributor_agreement_documents = NULL;

  /**
   * Signed Agreements by the user
   * @var unknown
   */
  private $user_contributor_agreement_documents = NULL;

  /**
   * Form field values
   *
   * @var array
   */
  private $form_fields = NULL;

  /**
   * Content for the Contributor aggrement form
   *
   * @var array
   */
  private $form_content = array();

  /**
   * Display Contributor notification flag
   *
   * @var string
   */
  private $display_notificaiton = TRUE;

  /**
   * Eclipse Friend object
   *
   * @var stdClass
   */
  private $Friend = NULL;

  /**
   * LDAP UID of the user
   * @var string
   */
  private $ldap_uid =  '';

  /**
   * Current state of contributor agreement
   * @var string
   */
  private $eca = TRUE;

  /**
   * URL of ECA document
   *
   * https://eclipse.local:50243/legal/ECA.html
   * @var string
   */
  private $eca_url = "http://www.eclipse.org/legal/ECA.html";

  public function Cla(App $App) {
    // Load the user
    $this->App = $App;
    $Session = $this->App->useSession();
    $this->Friend = $Session->getFriend();
    $this->ldap_uid = $this->Friend->getUID();

    // Load contributor agreement documents
    $this->_setContributorDocuments();
    $this->_setUserContributorSignedDocuments();

    // Get the current state
    $state = filter_var($this->App->getHTTPParameter("state", "POST"), FILTER_SANITIZE_STRING);
    $form = filter_var($this->App->getHTTPParameter("form_name", "POST"), FILTER_SANITIZE_STRING);

    if (!empty($this->ldap_uid) && $form == "cla-form") {
      switch ($state) {
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
    $this->notifyUserOfUnsignedCla();
  }

  private function _setEca($eca = TRUE) {
    if (is_bool($eca)) {
      $this->eca = $eca;
    }
    return $this->eca;
  }

  public function getEca() {
    return $this->eca;
  }

  /**
   * Get CLA Document Id
   * @return string
   */
  public function getClaDocumentId() {
     return 'a6f31f81d1b9abbcdbba';
  }

  /**
   * Get ECA Document Id
   * @return string
   */
  public function getEcaDocumentId() {
    return '99f64b0dac3e41dc1e97';
  }

  /**
   * Return CLA document id if still valid,
   * otherwise return eca document id
   *
   * @return string
   */
  public function getContributorDocumentId() {
    if (!$this->getEca()) {
      return $this->getClaDocumentId();
    }
    return $this->getEcaDocumentId();
  }

  /**
   * Get Display CLA notification flag
   * @return boolean|string
   */
  public function getDisplayNotification() {
    return $this->display_notificaiton;
  }

  /**
   * Set Display CLA notification flag
   *
   * @param string $value
   * @return boolean|string
   */
  public function setDisplayNotification($value = TRUE) {
    if (is_bool($value)) {
      $this->display_notificaiton = $value;
    }
    return $this->display_notificaiton;
  }

  /**
   * This function let the user know about an unsigned CLA
   *
   * @return boolean
   */
  public function notifyUserOfUnsignedCla() {
    // Verify if the display notification flag was disabled
    if (!$this->getDisplayNotification()) {
      return FALSE;
    }

    // We don't need to display the nofication if the user already signed the cla
    if ($this->getClaIsSigned()) {
      return FALSE;
    }

    // Check if user don't want to see the notification
    if (isset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION']) && $_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION'] === '1') {
      return FALSE;
    }

    $committer_string = '';
    if ($this->Friend->getIsCommitter()) {
      $committer_string = ' for which you are not a committer ';
    }

    $message = '
      <p>In order to contribute code to an Eclipse Foundation Project ' . $committer_string . 'you will be required to sign a Eclipse Contributor Agreement (ECA).</p>
      <form action="" method="POST">
        <input type="hidden" name="unsigned_cla_notification" value="1">
        <input type="hidden" name="state" value="disable_unsigned_notification">
        <input type="hidden" name="form_name" value="cla-form">
        <ul class="list-inline margin-top-10 margin-bottom-0">
          <li><a class="small btn btn-primary" href="http://www.eclipse.org/legal/clafaq.php">What is a CLA?</a></li>
          <li><a class="small btn btn-primary" href="#open_tab_cla">Sign your CLA</a></li>
          <li><button class="small btn btn-primary">Disable this message</button></li>
        </ul>
      </form>';

    $this->App->setSystemMessage('unsigned_cla',$message,'info');
  }

  /**
   * This function returns the CLA expiry date
   *
   * @return string
   */
  public function getClaExpiryDate() {
    $user_documents = $this->_getUserContributorSignedDocuments();
    if (!empty($user_documents[$this->getContributorDocumentId()]['EffectiveDate'])) {
      return date("Y-m-d", strtotime('+3 years', strtotime($user_documents[$this->getContributorDocumentId()]['EffectiveDate'])));
    }

    return '';
  }

  /**
   * These functions returns the text to put on the CLA form
   *
   * @param string $key
   * @return NULL|string|string
   */
  public function getClaFormContent($key = "") {
    if (!empty($key) && isset($this->form_content[$key])) {
      return $this->form_content[$key];
    }
    return '';
  }

  /**
   * This function sets the CLA fields
   * values from what's being posted from the form
   *
   * @param string $field
   * @return mixed
   */
  public function getFieldValues($field = "") {
    if (is_null($this->form_fields)) {
      $this->form_fields = array(
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
    }

    // Return the field if we're asking for one in particular
    if (!empty($field)) {
      if (empty($this->form_fields[$field])) {
        return '';
      }
      return $this->form_fields[$field];
    }

    return $this->form_fields;
  }

  /**
   * Set contributor_agreement_documents
   * @return Array
   */
  protected function _setContributorDocuments() {
    $this->contributor_agreement_documents = array();
    $sql = "SELECT * FROM SYS_Documents
    WHERE DocumentID = " . $this->App->returnQuotedString($this->getClaDocumentID()) . " or " .
    $this->App->returnQuotedString($this->getECADocumentID()) . " AND Version=1 AND Type='IN'";
    $result = $this->App->foundation_sql($sql);
    while ($row = mysql_fetch_assoc($result)) {
       $this->contributor_agreement_documents[$row['DocumentID']] = $row;
    }
    return $this->contributor_agreement_documents;
  }

  /**
   * Get contributor_agreement_documents
   * @return Array
   */
  protected function _getContributorDocuments(){
    if (is_null($this->contributor_agreement_documents)) {
      $this->_setContributorDocuments();
    }
    return $this->contributor_agreement_documents;
  }

  /**
   * Set user_contributor_agreement_documents
   *
   * @return array
   */
  protected function _setUserContributorSignedDocuments(){
    $this->user_contributor_agreement_documents = array();
    $sql = "SELECT PersonID, EffectiveDate, DocumentID
    FROM PeopleDocuments
    WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)) . "
    AND (DocumentID = " . $this->App->returnQuotedString($this->getClaDocumentID()) . " or " .
    $this->App->returnQuotedString($this->getECADocumentID()) . ")
    AND ExpirationDate IS NULL";
    $result = $this->App->foundation_sql($sql);

    while ($row = mysql_fetch_assoc($result)) {
       $this->user_contributor_agreement_documents[$row['DocumentID']] = $row;
    }

    if (!empty($this->user_contributor_agreement_documents[$this->getClaDocumentID()])) {
       $this->_setEca(FALSE);
    }
    return $this->user_contributor_agreement_documents;
  }

  /**
   * Set user_contributor_agreement_documents
   *
   * @return array
   */
  protected function _getUserContributorSignedDocuments(){
    if (is_null($this->user_contributor_agreement_documents)) {
      $this->_setUserContributorSignedDocuments();
    }
    return $this->user_contributor_agreement_documents;
  }

  /**
   * Verify if the user signed his CLA.
   *
   * @return boolean
   */
  public function getClaIsSigned($document_id = NULL) {

    if (is_null($document_id)) {
      $document_id = $this->getContributorDocumentId();
    }

    $user_documents = $this->_getUserContributorSignedDocuments();

    // If the array is empty, the user did not
    // sign the eca or cla.
    if (empty($user_documents)) {
      return FALSE;
    }

    if (!empty($user_documents[$document_id])) {
      return TRUE;
    }

    return FALSE;
  }


  /**
   * Generate HTML for CLA page
   */
  public function outputPage() {
    switch ($this->getClaIsSigned()){
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
   * @return mysql_query()
   */
  private function _actionLdapGroupRecord($action) {
    $email = $this->Friend->getEmail();
    $accepted_actions = array(
        'CLA_SIGNED',
        'CLA_INVALIDATED'
    );
    if ($this->ldap_uid && in_array($action, $accepted_actions) && !empty($email)) {
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
        ".$this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)).",
        'EclipseCLA-v1',
        ".$this->App->returnQuotedString($this->App->sqlSanitize($action)).",
        'cla_service',
        NOW()
      )";
      return  $this->App->eclipse_sql($sql);
    }
    $this->App->setSystemMessage('account_requests', "There's been an error updated the LDAP group record. (LDAP-01)", "danger");
  }

  /**
   * This function check if the current user has access to sign the CLA
   *
   * @return boolean
   */
  private function _allowSigning() {
    // If user is logged in
    $email = $this->Friend->getEmail();
    if (!empty($this->ldap_uid) || !empty($email)) {
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

    $cla_document = fopen($this->eca_url, 'r');
    $data = array(
      'legal_name' => $this->form_fields['Legal Name'],
      'public_name' => $this->form_fields['Public Name'],
      'employer' => $this->form_fields['Employer'],
      'address' => $this->form_fields['Address'],
      'email' => $this->form_fields['Email'],
      'question_1' => $this->form_fields['Question 1'],
      'question_2' => $this->form_fields['Question 2'],
      'question_3' => $this->form_fields['Question 3'],
      'question_4' => $this->form_fields['Question 4'],
      'agree' => $this->form_fields['Agree'],
      'cla_doc' => base64_encode(stream_get_contents($cla_document)),
    );
    fclose($cla_document);
    return json_encode($data);
  }

  /**
   * This function fetches content from the CLA html file
   */
  private function _claFormContent() {

    $cla_document = new DomDocument();
    $cla_document->loadhtmlfile($this->eca_url);

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
    $text4 = $cla_document->getElementById('text4');

    $this->form_content = array(
      'question_1' => $question1->nodeValue,
      'question_2' => $question2->nodeValue,
      'question_3' => $question3->nodeValue,
      'question_4' => $question4->nodeValue,
      'text_1' => $cla_document->saveXML($text1),
      'text_2' => $cla_document->saveXML($text2),
      'text_3' => $cla_document->saveXML($text3),
      'text_4' => $cla_document->saveXML($text4),
    );
  }

  /**
   * This function creates a new people record in the foundationDB
   * if it can't find an existing one
   *
   * @return bool
   */
  private function _createPeopleRecordIfNecessary() {

    if (empty($this->ldap_uid)) {
      return FALSE;
    }

    $sql = "SELECT PersonID FROM People
    WHERE PersonID = " . $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid));
    $result = $this->App->foundation_sql($sql);

    if ($row = mysql_fetch_assoc($result)) {
      if (isset($row['PersonID']) && !empty($row['PersonID'])) {
        return TRUE;
      }
    }

    $sql = "INSERT INTO People
      (PersonID, FName, LName, Type, IsMember, Email, IsUnixAcctCreated)
      values (
      ". $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)) .",
      ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getFirstName())) .",
      ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getLastName())) .",
      'XX',
      0,
      ". $this->App->returnQuotedString($this->App->sqlSanitize($this->Friend->getEmail())) .",
      0
    )";
    $result_insert = $this->App->foundation_sql($sql);

    // Log that this event occurred
    $sql = "INSERT INTO SYS_ModLog
      (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
      VALUES (
      'cla',
      'cla_service',
      'EclipseCLA-v1',
      'NEW PEOPLE RECORD',
      ". $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)) .",
      NOW()
    )";
    $result_log = $this->App->foundation_sql($sql);


    return (bool)$result_insert;
  }

  /**
   * This function sets a cookie to hide the unsigned notification message
   * */
  private function _disableUnsignedNotification() {
    setcookie ('ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION', '1',  time() + 3600 * 24 * 1095, '/' );
    $this->setDisplayNotification(FALSE);
  }

/**
 * This function invalidates a user's CLA document
 */
  private function _invalidateClaDocument() {
    $document_id = $this->getContributorDocumentId();
    $user_documents = $this->_getUserContributorSignedDocuments();
    $document = $user_documents[$document_id];

    if (!empty($this->ldap_uid)  && !empty($document['EffectiveDate'])) {
      // Log that this event occurred Note that foundationdb uses SYS_ModLog instead of SYS_EvtLog;
      $sql = "INSERT INTO SYS_ModLog
        (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
        values (
        'cla',
        'cla_service',
        'EclipseCLA-v1',
        'INVALIDATE_CLA DOCUMENT',
        ".$this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)).",
        NOW()
      )";
      $result = $this->App->foundation_sql($sql);

      // Invalidate the users LDAP group.
      $this->_actionLdapGroupRecord('CLA_INVALIDATED');

      $invalidated = FALSE;
      $loop = 0;

      while($loop < 10) {
        // Wait 1 second for the Perl script to invalidate
        // the user's CLA/ECA in the PeopleDocuments table
        sleep(1);

        // Perform another Select to find out if the user
        // still has a valid CLA/ECA
        $this->_setUserContributorSignedDocuments();

        if ($this->getClaIsSigned() == FALSE) {
          $invalidated = TRUE;
          break;
        }
        $loop++;
      }

      if ($invalidated) {

        // Making sure we add the notification back in the page
        if (isset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION'])) {
          unset($_COOKIE['ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION']);
          setcookie('ECLIPSE_CLA_DISABLE_UNSIGNED_NOTIFICATION', '', time() - 3600, '/');
        }

        // Create success message
        $this->App->setSystemMessage('invalidate_cla','You have successfully invalidated your CLA.','success');
        return TRUE;
      }
      $this->App->setSystemMessage('invalidate_cla','We were unable to invalidate the CLA we have on record. (LDAP-02)','danger');
      return FALSE;
    }

    $this->App->setSystemMessage('invalidate_cla','An attempt to invalidate the CLA failed because we were unable to find the CLA that matches. (LDAP-03)','danger');
    return FALSE;
  }

  /**
   * This internal function inserts a new CLA document based off the form data submitted.
   */
  private function _submitClaDocument() {
    // Check if the sumitted fields validate and if there is no signed CLA for this user
    $document_id = $this->getEcaDocumentId();
    if ($this->_allowSigning() && $this->_validatedClaFields() && !$this->getClaIsSigned($document_id)) {

      $this->_createPeopleRecordIfNecessary();

      // get the CLA document in Json format
      $blob = $this->_claDocumentInJson();

      $sql = "INSERT INTO PeopleDocuments
        (PersonId,DocumentId,Version,EffectiveDate,ReceivedDate,
        ScannedDocumentBLOB,ScannedDocumentMime,ScannedDocumentBytes,
        ScannedDocumentFileName,Comments)
        VALUES (
        ". $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)) .",
        ". $this->App->returnQuotedString($this->App->sqlSanitize($document_id)) .",
        1,
        now(),
        now(),
        '". $blob ."',
        'application/json',
        ". strlen($blob) .",
        'eclipse-eca.json',
        'Automatically generated CLA'
      )";
      $result = $this->App->foundation_sql($sql);

      // Log that this event occurred
      $sql = "INSERT INTO SYS_ModLog
        (LogTable,PK1,PK2,LogAction,PersonID,ModDateTime)
        VALUES (
        'cla',
        ". $this->App->returnQuotedString($this->App->sqlSanitize($this->ldap_uid)) .",
        'EclipseCLA-v1',
        'NEW CLA DOCUMENT',
        'cla_service',
        NOW()
      )";
      $result = $this->App->foundation_sql($sql);

      // Submit the users LDAP group.
      $this->_actionLdapGroupRecord('CLA_SIGNED');
      $this->App->setSystemMessage('submit_cla',"You successfully submitted the CLA!",'success');
      $this->_setUserContributorSignedDocuments();
      return TRUE;
    }

    $this->App->setSystemMessage('submit_cla',"Error, the CLA have not been submitted. (LDAP-03)",'danger');
    return FALSE;
  }

  /**
   * This function checks if all the fields from the form validates
   *
   * @return BOOL
   *
   */
  private function _validatedClaFields() {
    $form_fields = $this->getFieldValues();
    foreach ($form_fields as $field_name => $field_value) {
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

    if (!isset($is_valid)) {
      return TRUE;
    }

    return FALSE;
  }

}