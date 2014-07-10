<?php ob_start(); ?>
<div class="toolbar-menu">
  <div class="container">
    <div class="row">
      <div class="col-md-24">
        <ol class="breadcrumb">
          <li><i class="fa fa-angle-double-right orange fa-fw"></i> <a class="active" href="/downloads/index.php">Packages</a></li>
          <li><a href="/downloads/java8/">Java&trade; 8 Support</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-toolbarmenu">Toolbar Menu</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>