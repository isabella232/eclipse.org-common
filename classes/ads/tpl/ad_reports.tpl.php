<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation)  - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<?php $impressions = $this->getImpressions();?>
<form method="POST" action="">
  <input name="state" value="exportCsv" type="hidden">
  <input class="btn btn-primary" type="submit" value="Export CSV" name="exportCsv">
</form>
<hr>
<table class="table">
  <thead>
    <tr>
      <th>campaignKey</th>
      <th>date</th>
      <th>impressions</th>
      <th>clicks</th>
      <th>Ratio (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($impressions as $row):?>
      <tr>
        <td><?php print $row['campaignKey']?></td>
        <td><?php print $row['date']?></td>
        <td><?php print $row['impressions']?></td>
        <td><?php print $row['clicks']?></td>
        <td><?php print $row['ratio']?>%</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>