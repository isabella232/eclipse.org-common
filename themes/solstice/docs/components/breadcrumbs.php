<?php ob_start(); ?>
<section class="default-breadcrumbs hidden-print">
  <div class="container">
    <ol class="breadcrumb">
      <li><a href="https://www.eclipse.org/">Home</a></li>
      <li><a href="https://www.eclipse.org/projects/">Projects</a></li>
      <li><a href="https://www.eclipse.org/eclipse.org-common">eclipse.org-common</a></li>
      <li class="active">Solstice documentation</li>
    </ol>
  </div>
</section><?php $html = ob_get_clean();?>

<h3 id="section-breadcrumbs">Breadcrumbs</h3>
<p>The <code>$App Class</code> should generate a breadcrumb for you.</p>
</div>
<?php print $html;?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>

