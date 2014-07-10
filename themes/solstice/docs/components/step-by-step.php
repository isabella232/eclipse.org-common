<?php ob_start(); ?>
<div class="step-by-step">
  <div class="container">
    <div class="row intro">
      <div class="col-xs-24">
        <h2>Participate &amp; Contribute</h2>
        <p>Get involved in Eclipse projects to help contribute to their success.<br>
          We welcome users and adopters as part of the community.
        </p>
      </div>
    </div>
    <div class="row step-by-step-timeline">
      <div class="col-sm-6 step">
        <a href="/contribute"><img href="How to contribute" src="/projects/images/projects_contribute.jpg"></a>
        <p><a class="btn btn-info uppercase fw-700" href="/contribute">How to contribute</a></p>
      </div>
      <div class="col-sm-6 step">
        <a href="//wiki.eclipse.org/Development_Resources/HOWTO/Starting_A_New_Project"><img href="Start a new project" src="/projects/images/projects_start.jpg"></a>
        <p><a class="btn btn-info uppercase fw-700" href="//wiki.eclipse.org/Development_Resources/HOWTO/Starting_A_New_Project">Start a new project</a></p>
      </div>
      <div class="col-sm-6 step">
        <a href="//wiki.eclipse.org/Development_Resources/HOWTO/Starting_A_New_Project#After_Creation"><img href="Running a project" src="/projects/images/projects_running.jpg"></a>
        <p><a class="btn btn-info uppercase fw-700" href="//wiki.eclipse.org/Development_Resources/HOWTO/Starting_A_New_Project#After_Creation">Running a project</a></p>
      </div>
      <div class="col-sm-6 step">
        <a href="/projects/project_activity.php"><img href="Project Activity" src="/projects/images/projects_news.jpg"></a>
        <p><a class="btn btn-info uppercase fw-700" href="/projects/project_activity.php">Project Activity</a></p>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-stepbystep">Step by Step</h3>
<?php print $html; ?>
<h4>Code</h4>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>