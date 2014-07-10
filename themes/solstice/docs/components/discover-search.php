<?php ob_start(); ?>
<div class="discover-search">
  <div class="container">
    <div class="col-xs-24">
      <div class="row">
        <h2>Discover</h2>
        <p class="orange"><strong>Find an Eclipse open source project.</strong></p>
        <form action="https://projects.eclipse.org/projects/all" role="form" class="col-md-8 form-inline form-search-projects input-group custom-search-form" id="form-discover-search">
          <input type="text" placeholder="Search" name="keys" size="25" class="form-control" id="discover-search-box">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default">
              <i class="fa fa-search"></i>
            </button>
          </span>
        </form>
        <br>
        <p><a href="//projects.eclipse.org/list-of-projects" class="btn btn-info uppercase fw-700">List of projects</a></p>
      </div>
    </div>
  </div>
</div><?php $html = ob_get_clean();?>

<h3 id="section-discover-search">Discover Search</h3>
<p>The discover search component is used on the <a href="https://eclipse.org/projects/"></a>Eclipse Projects landing page.</p>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>