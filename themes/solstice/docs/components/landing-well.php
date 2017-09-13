<?php ob_start(); ?>
<section class="landing-well">
  <div class="container">
    <div class="row">
      <div class="col-lg-13 landing-well-content">
        <h1><a href="/org/">Eclipse Is...</a></h1>
        <p>An amazing open source community of <a href="/ide">Tools</a>, <a href="/projects">Projects</a> and <br><a href="/org/workinggroups">Collaborative Working Groups</a>. Discover what we have to offer and join us.</p>
        <br>
        <div class="btn-group">
          <a class="btn btn-transparent" href="#sec_ide">
            DISCOVER <span class="caret"></span>
          </a>
        </div>
      </div>
      <div class="col-lg-10 col-lg-offset-1 landing-well-action">
        <ul class="list-inline list-glyphicon">
          <li>
            <a href="/ide/">
              <div class="col-md-8 circle circle-dark">
                <i class="fa fa-desktop"></i>
                 <h3 style="margin:32px 0 0 5px;">IDE &amp; Tools</h3>
              </div>
            </a>
          </li>
          <li>
            <a href="/projects/">
              <div class="col-md-8 circle circle-dark">
                <i class="fa fa-puzzle-piece" style="margin-left:10px;"></i>
                <h3>Community of Projects</h3>
              </div>
            </a>
          </li>
          <li>
            <a href="/org/workinggroups/">
              <div class="col-md-8 circle circle-dark">
                <i class="fa fa-users"></i>
                <h3>Collaborative Working Groups</h3>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>
<?php $html = ob_get_clean();?>

<h3 id="section-landing-well">Landing-well</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>