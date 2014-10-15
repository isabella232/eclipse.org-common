<?php ob_start(); ?>
<div class="block-box block-box-classic">
  <h3>Block Title</h3>
  <div class="content">
    <p>Content goes here...</p>
  </div>
</div><?php $html = ob_get_clean();?>

<h3 id="section-block-box">Block-box</h3>
<p>Content block mainly used in the right sidebar area. The <code>.block-box-classic</code> class is optional.</p>
<?php print $html;?>

<h4>Code</h4>

<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>