<?php

  $theme_path = $_SERVER["DOCUMENT_ROOT"] . "/eclipse.org-common/themes/solstice/";
  require_once($theme_path . 'classes/SolsticeBtnCfa.class.php');

  // Initialize $variables.
  $variables = array();
  // CFA Link - Big orange button in header
  $variables['btn_cfa'] = array(
    'hide' => FALSE, // Optional - Hides the CFA button.
    'html' => '', // Optional - Replace CFA html and insert custom HTML.
    'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
    'href' => '//www.eclipse.org/downloads/', // Optional - Replace href on CFA link.
    'text' => '<i class="fa fa-download"></i> Download' // Optional - Replace text of CFA link.
  );

  $SolsticeBtnCfa = New SolsticeBtnCfa($variables['btn_cfa']);

?>
<?php ob_start(); ?>
<?php print $SolsticeBtnCfa->build();?>
<?php $html = ob_get_clean();?>

<h3 id="section-btncfa">Call For Action Button link</h3>
<p>Update or replace the CFA buttonin the header of solstice.</p>
<?php print $html;?>

<h4>PHP Code</h4>
<div class="editor" data-editor-lang="html" data-editor-no-focus="true">
&lt;?php

  $variables = array();

  // CFA Link - Big orange button in header
  $variables['btn_cfa'] = array(
    'hide' => FALSE, // Optional - Hide the CFA button.
    'html' => '', // Optional - Replace CFA html and insert custom HTML.
    'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
    'href' => '//www.eclipse.org/downloads/', // Optional - Replace href on CFA link.
    'text' => '&lt;i class="fa fa-download"&gt;&lt;/i&gt; Download' // Optional - Replace text of CFA link.
  );

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);

</div>
<h4>HTML Output</h4>

<div class="editor" data-editor-lang="html" data-editor-no-focus="true"><?php print htmlentities($html); ?></div>