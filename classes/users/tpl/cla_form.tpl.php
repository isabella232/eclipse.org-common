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
?>
<?php if (is_a($this, 'Cla') && $this->Friend->getUID()): ?>
  <form id="frm_cla" name="frm_cla" action="#open_tab_cla" method="post">
    <?php print $this->getClaFormContent('text_1'); ?>
    <div class="well">
      <h2>YOU ACCEPT ...</h2>

      <div class="form-group clearfix">
        <div class="col-xs-1 position-static">
          <input <?php if ($this->getFieldValues('Question 1') === "1"){print 'checked';}?>
          class="committer-license-agreement-checkbox form-checkbox required"
          type="checkbox" id="edit-question-1" name="question_1" value="1" />
        </div>
        <div class="col-xs-22">
          <label class="option" for="edit-question-1">Question 1 <span
          class="form-required" title="This field is required.">*</span></label>
          <div class="description"><?php print $this->getClaFormContent('question_1'); ?></div>
        </div>
      </div>

      <div class="form-group clearfix">
        <div class="col-xs-1 position-static">
      <input <?php if ($this->getFieldValues('Question 2') === "1"){print 'checked';}?>
        class="committer-license-agreement-checkbox form-checkbox required"
        type="checkbox" id="edit-question-2" name="question_2" value="1" />
      </div>
        <div class="col-xs-22">
      <label class="option" for="edit-question-2">Question 2 <span
        class="form-required" title="This field is required.">*</span></label>
      <div class="description"><?php print $this->getClaFormContent('question_2'); ?></div>
        </div>
      </div>

      <div class="form-group clearfix">
        <div class="col-xs-1 position-static">
      <input <?php if ($this->getFieldValues('Question 3') === "1"){print 'checked';}?>
        class="committer-license-agreement-checkbox form-checkbox required"
        type="checkbox" id="edit-question-3" name="question_3" value="1" />
      </div>
        <div class="col-xs-22">
      <label class="option" for="edit-question-3">Question 3 <span
        class="form-required" title="This field is required.">*</span></label>
      <div class="description"><?php print $this->getClaFormContent('question_3'); ?></div>
        </div></div>

      <div class="form-group clearfix">
        <div class="col-xs-1 position-static">
      <input <?php if ($this->getFieldValues('Question 4') === "1"){print 'checked';}?>
        class="committer-license-agreement-checkbox form-checkbox required"
        type="checkbox" id="edit-question-4" name="question_4" value="1" />
      </div>
        <div class="col-xs-22">
      <label class="option" for="edit-question-4">Question 4 <span
        class="form-required" title="This field is required.">*</span></label>
      <div class="description"><?php print $this->getClaFormContent('question_4'); ?></div>
      </div></div>

      <div class="form-group">
      <?php print $this->getClaFormContent('text_2'); ?>
      </div>
      <div class="form-group">
      <label for="edit-agree">Electronic Signature <span
        class="form-required" title="This field is required.">*</span></label>
      <input class="form-control form-text required" type="text"
        id="edit-cla-agree" name="cla_agree" value="<?php print $this->getFieldValues('Agree'); ?>" size="60" maxlength="128" />
      <div class="description">Type &quot;I AGREE&quot; to accept the
        terms above</div>
      </div>
    </div>


    <?php print $this->getClaFormContent('text_3'); ?>

    <div class="form-group">
      <label for="edit-email">Email Address <span class="form-required"
        title="This field is required.">*</span></label>
      <input readonly class="form-control form-text"
        type="text" id="edit-email" name="email"
        value="<?php print $this->Friend->getEmail(); ?>" size="60" maxlength="128" />
      <div class="description">If you wish to use a different email
        address you must first change the primary email address associated
        with your account</div>

    </div>
    <div class="form-group">
      <label for="edit-legal-name">Legal Name <span class="form-required"
        title="This field is required.">*</span></label>
      <input readonly
        class="form-control form-text" type="text"
        id="edit-legal-name" name="legal_name" value="<?php print $this->Friend->getFirstName() . ' ' . $this->Friend->getLastName(); ?>"
        size="60" maxlength="128" />
      <div class="description">Your full name as written in your passport
        (e.g. First Middle Lastname)</div>
    </div>

    <div class="form-group">
      <label for="edit-public-name">Public Name </label>
      <input
        class="form-control form-text" type="text" id="edit-public-name"
        name="public_name" value="<?php print $this->getFieldValues('Public Name'); ?>" size="60" maxlength="128" />
      <div class="description">Your full name, alias, or nickname that
        people call you in the Project (e.g. First Lastname) - leave this
        field empty if it&#039;s identical to your legal name</div>
    </div>

    <div class="form-group">
      <label for="edit-employer">Employer <span class="form-required"
        title="This field is required.">*</span></label> <input
        class="form-control form-text required" type="text"
        id="edit-employer" name="employer" value="<?php print $this->getFieldValues('Employer'); ?>" size="60"
        maxlength="128" />
      <div class="description">Your employer - you may choose to enter
        &quot;Self-employed&quot; or &quot;Student&quot; in this field</div>
    </div>

    <div class="form-group">
      <label for="edit-address">Mailing Address <span
        class="form-required" title="This field is required.">*</span></label>
      <div class="form-textarea-wrapper resizable">
        <textarea class="form-control form-textarea required"
          id="edit-address" name="address" cols="60" rows="5"><?php print $this->getFieldValues('Address'); ?></textarea>
      </div>
      <div class="description">Your physical mailing address</div>
    </div>

    <div class="form-group">
      <input type="hidden" name="state" value="submit_cla">
      <input type="hidden" name="form_name" value="cla-form">
      <button class="btn btn-default form-submit" id="edit-submit" name="op"
        value="Accept" type="submit">Accept</button>
      </div>
      <p class="help_text">
        If you have any questions about this agreement, licensing, or
        anything related to intellectual property at the Eclipse Foundation,
        please send an email to <a href="mailto:license@eclipse.org">license@eclipse.org</a>.
      </p>
  </form>
<?php endif; ?>