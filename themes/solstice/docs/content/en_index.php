
<style>
hr{
  margin-bottom:3em;
}
</style>
<h1><?php print $pageTitle;?></h1>

<p>The Solstice theme was built on top of Bootstrap which is a sleek, intuitive,
and powerful front-end framework for faster and easier web development.</p>
<p>We support most UI components from <a href="https://wiki.eclipse.org/Nova">Nova</a>. We’re hoping that the transition won’t be too hard for most pages.</p>
<h2>What's included with Solstice?</h2>
<ul>
  <li><a href="http://getbootstrap.com/">Bootstrap</a> v3.2.0</li>
  <li><a href="http://bootstrapvalidator.com/">BootstrapValidator</a> v0.4.5</li>
  <li><a href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a> v4.1.0 <span class="red">*Glyphicons is not included with Solstice.</span></li>
  <li><a href="http://jquery.com/">jQuery</a> v2.1.1</li>
  <li><a href="https://github.com/chrisguindon/solstice-assets">Solstice Assets</a> (Less files &amp; images)</li>
  <li><a href="http://geedmo.github.io/yamm3/">Yamm3</a> (Yet another megamenu for Bootstrap 3)</li>
</ul>

<h2>Getting Started</h2>
<ul>
  <li><a href="https://www.youtube.com/watch?v=AbSC3506sz0&feature=youtu.be">Committer and Contributor Hangout Series -- Eclipse Website Refresh</a></li>
  <li>Read the documentation for <a href="http://getbootstrap.com/css/">Bootstrap</a>, <a href="http://bootstrapvalidator.com/">BootstrapValidator</a> &amp; <a href="http://jquery.com/">jQuery</a></li>
  <li><a href="http://wiki.eclipse.org/Using_Phoenix">How to use Phonix</a></li>
</ul>

<h2>Using Solstice</h2>
<p>On a page using the eclipse.org-common $App Class:</p>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true">
&lt;?php
$App->generatePage(&apos;solstice&apos;, $Menu, NULL, $pageAuthor, $pageKeywords, $pageTitle, $html);

// To make sure your page is always using the default theme:
$App->generatePage(NULL, $Menu, NULL , $pageAuthor, $pageKeywords, $pageTitle, $html);

</div>

<h2 id="starterkit">Starterkit</h2>
<p>The <a href="/eclipse.org-common/themes/solstice/docs/starterkit/">starterkit</a> includes all the files required to create a page with Solstice. The source code is available <a href="http://git.eclipse.org/c/www.eclipse.org/eclipse.org-common.git/tree/themes/solstice/docs/starterkit/">here</a>.</p>
<p> <p><a href="/eclipse.org-common/themes/solstice/docs/starterkit/solstice-starterkit.zip" class="btn btn-warning">Download Starterkit</a></p></p>
<br/>

<h2>Theme variables</h2>
<p>It's now possible to alter the Solstice theme using <code>$App->setThemeVariables($variables);</code>.</p>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true">
&lt;?php

  // Initialize $variables.
  $variables = array();

  // Add classes to &lt;body&gt;. (String)
  $variables['body_classes'] = '';

  // Insert custom HTML in the breadcrumb region. (String)
  $variables['breadcrumbs_html'] = "";

  // Hide the breadcrumbs. (Bool)
  $variables['hide_breadcrumbs'] = TRUE;

  // Insert HTML before the left nav. (String)
  $variables['leftnav_html'] = '';

  // Update the main container class, this is usefull if you want to use the full width of the page. (String)
  // Eclipse.org homepage is a good example: https://www.eclipse.org/home/index.php
  $variables['main_container_classes'] = 'container-full';

  // Insert HTML after opening the main content container, before the left sidebar. (String)
  $variables['main_container_html'] = '';

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);
</div>

<h2>Barebones static HTML template</h2>
<p>A barebone HTML header &amp; footer to adapt the look to subsites, such as Bugzilla, Forums, Mailing lists &amp; events.eclipse.org.</p>

<h3>Default template</h3>
<ul>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/header.php">Header</a></li>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/header.php">Footer</a></li>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/">Full page</a></li>
</ul>
<h3>Thin template <small>(<a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=437384">Bug #437384</a>)</small></h3>
<ul>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/thin/header.php">Thin header</a></li>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/thin/">Full page</a></li>
</ul>
<h3 id="tpl-barebone">Barebone template</h3>
<ul>
  <li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/barebone/header.php">Thin header</a></li>
</ul>
<br/>

<h2>CSS</h2>
<p><a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/classes.less">classes.less</a>
and <a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/fonts.less">fonts.less</a> include usefull CSS classes for
colors, font-weight &amp; font size and offsets to remove the margin after the breadcrumbs or before the footer.</p>

<p><a href="typo.php">Typography</a> examples with solstice.</p>

<h2>Custom Components</h2>
<ol>
  <li><a href="#section-block-box">Block-box</a></li>
  <li><a href="#section-breadcrumbs">Breadcrumbs</a></li>
  <li><a href="#section-discover-search">Discover Search</a></li>
  <li><a href="#section-dragdrop">Marketplace Drag and Drop install</a></li>
  <li><a href="#section-headerrow">Header row</a></li>
  <li><a href="#section-highlight">Highlight</a></li>
  <li><a href="#section-landing-well">Landing well</a></li>
  <li><a href="#section-news-list">News list</a></li>
  <li><a href="#section-stepbystep">Step by Step</a></li>
  <li><a href="#section-timeline">Timeline</a></li>
  <li><a href="#section-toolbarmenu">Toolbar Menu</a></li>
</ol>

<?php include('components/block-box.php');?><hr/>
<?php include('components/breadcrumbs.php');?><hr/>
<?php include('components/discover-search.php');?><hr/>
<?php include('components/dragdrop.php');?><hr/>
<?php include('components/headerrow.php');?><hr/>
<?php include('components/hightlight.php');?><hr/>
<?php include('components/landing-well.php');?><hr/>
<?php include('components/news-list.php');?><hr/>
<?php include('components/step-by-step.php');?><hr/>
<?php include('components/timeline.php');?><hr/>
<?php include('components/toolbar-menu.php');?><hr/>
