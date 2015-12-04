<?php $impressions = $this->getImpressions();?>
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