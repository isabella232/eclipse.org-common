<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if(!is_a($this, 'MailingLists') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}

$mailing_lists = $this->getMailingLists();
$newsgroups = $this->getNewsgroups();

if (isset($mailing_lists['completed'])) {
  unset($mailing_lists['completed']);
}

if (isset($newsgroups['completed'])) {
  unset($newsgroups['completed']);
}

print $this->getMailingListTable($mailing_lists, 'mailing_lists');
print $this->getMailingListTable($newsgroups, 'newsgroups');
