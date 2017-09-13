<?php

// Initialize $variables.
$variables = array();
$links = array();

$links[] = array(
  'icon' => 'fa-download', // Required
  'url' => '/downloads/', // Required
  'title' => 'Download', // Required
  //'target' => '_blank', // Optional
  'text' => 'Eclipse Distribution, Update Site, Dropins' // Optional
);

$links[] = array(
  'icon' => 'fa-users', // Required
  'url' => '/users/', // Required
  'title' => 'Geting Involved', // Required
  //'target' => '_blank', // Optional
  'text' => 'CVS, Workspace Setup, Wiki, Committers' // Optional
);

$links[] = array(
  'icon' => 'fa-book', // Required
  'url' => 'http://help.eclipse.org/luna/index.jsp', // Required
  'title' => 'Documentation', // Required
  //'target' => '_blank', // Optional
  'text' => 'Tutorials, Examples, Videos, Online Reference' // Optional
);

$links[] = array(
  'icon' => 'fa-support', // Required
  'url' => '/forums/', // Required
  'title' => 'Support', // Required
  //'target' => '_blank', // Optional
  'text' => 'Bug Tracker, Newsgroup Professional Support' // Optional
);

$variables = array(
  'links' =>  $links, // Required
  'logo' => array( // Required
    'src' => '/eclipse.org-common/themes/solstice/public/images/logo/eclipse-800x188.png', // Required
    'alt' => 'The Eclipse Foundation', // Optional
    'url' => 'http://www.eclipse.org', // Optional
    //'target' => '_blank' // Optional
  ),
);
?>


<h3 id="section-headernav">Header Nav</h3>
<p>Custom header navigation for project pages.</p><p><strong>For more information:</strong><br/> <a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108">Bug 436108</a> - Update navigation buttons for Documentation, Download, Getting Involved and Support for project pages.</p>
<?php
  $Theme->setThemeVariables(array('header_nav' => $variables));
  print $Theme->getHeaderNav();
?>

<h4>PHP Code</h4>
<pre>
&lt;?php
  // Initialize $variables.
  $variables = array();
  $links = array();

  $links[] = array(
    'icon' => 'fa-download', // Required
    'url' => '/downloads/', // Required
    'title' => 'Download', // Required
    //'target' => '_blank', // Optional
    'text' => 'Eclipse Distribution, Update Site, Dropins' // Optional
  );

  $links[] = array(
    'icon' => 'fa-users', // Required
    'url' => '/users/', // Required
    'title' => 'Geting Involved', // Required
    //'target' => '_blank', // Optional
    'text' => 'CVS, Workspace Setup, Wiki, Committers' // Optional
  );

  $links[] = array(
    'icon' => 'fa-book', // Required
    'url' => 'http://help.eclipse.org/luna/index.jsp', // Required
    'title' => 'Documentation', // Required
    //'target' => '_blank', // Optional
    'text' => 'Tutorials, Examples, Videos, Online Reference' // Optional
  );

  $links[] = array(
    'icon' => 'fa-support', // Required
    'url' => '/forums/', // Required
    'title' => 'Support', // Required
    //'target' => '_blank', // Optional
    'text' => 'Bug Tracker, Newsgroup Professional Support' // Optional
  );

  $variables['header_nav'] = array(
    'links' =>  $links, // Required
    'logo' => array( // Required
      'src' => '/eclipse.org-common/themes/solstice/public/images/logo/eclipse-800x188.png', // Required
      'alt' => 'The Eclipse Foundation', // Optional
      'url' => 'http://www.eclipse.org', // Optional
      //'target' => '_blank' // Optional
     ),
  );

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);
</pre>
<h4>HTML Output</h4>

<pre><?php print htmlentities($Theme->getHeaderNav()); ?></pre>