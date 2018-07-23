<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *   Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

// Check to see if a person or a group as been selected
$selection = $this->getCampaignByUserOrGroup();
if(empty($selection)):
  $this->setStatusMessage('Select a person or a group', 'warning');
else: ?>

  <h2><?php print $this->getCampaignByUserOrGroup();?> Campaigns</h2>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Group</th>
        <th>Key</th>
        <th>Clicks</th>
        <th>Target URL</th>
        <th>Created</th>
        <th>Expires</th>
        <th>Owner</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($this->viewCampaigns() as $myrow):?>
      <tr>
      <td>
        <?php print $myrow['CampaignGroup']; ?>
      </td>
      <td>
        <a href="http://eclipse.org/go/<?php print $myrow['CampaignKey']; ?>">
          <?php print $myrow['CampaignKey']; ?>
        </a>
      </td>
      <td>
        <form action="?page=view-clicks&<?php print $this->getCampaignByUserOrGroup($_for_url = TRUE); ?>"
         method="POST">
          <input class="form-control" type="hidden"
           name="campaignViewClicks" value="<?php print $myrow['CampaignKey']; ?>">
          <input class="form-control" type="submit"
           value="View <?php print $this->countCampaignClicks($myrow['CampaignKey']); ?> Clicks" name="viewCampaignClicks">
          <br>
          Max:
          <input type="text" name="campaignMaxClicks" value="100" size="6" maxlength="6">
          <input type="hidden" name="action_state"  value="view-clicks"/>
            <?php foreach($this->pastMonthClicks($myrow['CampaignKey']) as $pastDate ):?>
              <?php print '<br>' . $pastDate['date'] . ': ' . $pastDate['count']; ?>
            <?php endforeach; ?>
        </form>
      </td>

      <td><?php print $this->shortenedString($myrow['TargetUrl'],0,80); ?></td>
      <td><?php print $myrow['DateCreated']; ?></td>
      <td><?php print $myrow['DateExpires']; ?></td>
      <td><?php print $myrow['CreatorPortalID']; ?></td>

      <td>
        <form action="?page=view-campaigns&<?php print $this->getCampaignByUserOrGroup($_for_url = TRUE); ?>" method="POST">
          <input class="form-control" type="hidden" name="campaignDelete"
            value="<?php print $myrow['CampaignKey']; ?>">
            Confirm:
          <input type="checkbox" name="campaignConfirmDelete">
          <input type="hidden" name="action_state"  value="delete"/>
          <input type="submit" value="DELETE" name="delete" class="btn btn-primary">
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>