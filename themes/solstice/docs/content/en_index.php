
<style>
.editor{
  margin-bottom:7em;
}
</style>
<h1><?php print $pageTitle;?></h1>

<p>We designed the Solstice to be cleaner and lighter. We took a less is more approach. It was created on top of Bootstrap which is a sleek, intuitive,
and powerful front-end framework for faster and easier web development.</p>
<p>We support most UI components from <a href="https://wiki.eclipse.org/Nova">Nova</a>. We’re hoping that the transition won’t be too hard for most use-case.</p>
<h2>What's included with Solstice?</h2>
<ul>
  <li><a href="http://getbootstrap.com/">Bootstrap</a> v3.1.1</li>
  <li><a href="http://bootstrapvalidator.com/">BootstrapValidator</a> v0.4.5</li>
  <li><a href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a> <span class="red">*Glyphicons is not included with Solstice.</span></li>
  <li><a href="http://jquery.com/">jQuery</a> v2.1.1</li>
  <li><a href="https://github.com/chrisguindon/solstice-assets">Solstice Assets</a> (Less files &amp; images)</li>
  <li><a href="http://geedmo.github.io/yamm3/">Yamm3</a> (Yet another megamenu for Bootstrap 3)</li>
</ul>


<h2>Getting Started</h2>
<ul>
  <li>Read the documentation for <a href="http://getbootstrap.com/css/">Bootstrap</a>, <a href="http://bootstrapvalidator.com/">BootstrapValidator</a> & <a href="http://jquery.com/">jQuery</a></li>
  <li><a href="http://wiki.eclipse.org/Using_Phoenix">How to use Phonix</a></li>
</ul>

<h2>Using Solstice</h2>
<p>On a page using the eclipse.org-common $App Class:</p>
<pre>
&lt?php
$App->generatePage('solstice', $Menu, NULL , $pageAuthor, $pageKeywords, $pageTitle, $html);

// To make sure your page is always using the default theme:
//$App->generatePage(NULL, $Menu, NULL , $pageAuthor, $pageKeywords, $pageTitle, $html);

</pre>

<h2>Barebones static HTML template</h2>
<p>A barebone HTML header & footer to adapt the look to subsites, such as Bugzilla, Forums, Mailing lists & events.eclipse.org.</p>
<ul>
<li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/header.php">Header</a></li>
<li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/header.php">Footer</a></li>
<li><a href="https://eclipse.org/eclipse.org-common/themes/solstice/html_template/">Full page</a></li>
</ul>

<h2>CSS</h2>
<p><a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/classes.less">classes.less</a>
and <a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/fonts.less">fonts.less</a> include usefull CSS classes for
colors, font-weight &amp; font size and offsets to remove the margin after the breadcrumbs or before the footer.</p>

<h2>Custom Components</h2>
<ol class="">
  <li><a href="#section-block-box">Block-box</a></li>
  <li><a href="#section-breadcrumbs">Breadcrumbs</a></li>
  <li><a href="#section-discover-search">Discover Search</a></li>
  <li><a href="#section-drapdrop">Marketplace Drag and Drop install</a></li>
  <li><a href="#section-headerrow">Header row</a></li>
  <li><a href="#section-hightlight">Hightlight</a></li>
  <li><a href="#section-landing-well">Landing well</a></li>
  <li><a href="#section-news-list">News list</a></li>
  <li><a href="#section-stepbystep">Step by Step</a></li>
  <li><a href="#section-timeline">Timeline</a></li>
  <li><a href="#section-toolbarmenu">Toolbar Menu</a></li>
</ol>

<?php include('components/block-box.php');?>
<?php include('components/breadcrumbs.php');?>
<?php include('components/discover-search.php');?>
<?php include('components/dragdrop.php');?>
<?php include('components/headerrow.php');?>
<?php include('components/hightlight.php');?>
<?php include('components/landing-well.php');?>
<?php include('components/news-list.php');?>
<?php include('components/step-by-step.php');?>
<?php include('components/timeline.php');?>
<?php include('components/toolbar-menu.php');?>
