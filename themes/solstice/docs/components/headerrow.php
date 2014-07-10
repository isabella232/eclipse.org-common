<?php ob_start(); ?>
<div class="row header-row background-charcoal">
  <div class="col-md-16 header-row float-right right">
    <span id="descriptionText">Eclipse Luna (4.4) Release</span> for
    <select id="osSelect">
      <option value="win32">Windows</option>
      <option value="linux" selected="selected">Linux</option>
      <option value="macosx">Mac OS X (Cocoa)</option>
    </select>
  </div>
</div><?php $html = ob_get_clean();?>

<h3 id="section-discover-headerrow">Header row</h3>
<p>@TODO</p>
<?php print $html; ?>

<h4>Code</h4>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>