<?php
/**
 * @file
 * Solstice footer
 */
?>
<p id="back-to-top">
  <a class="visible-xs" href="#top">Back to the top</a>
</p>
<footer role="contentinfo" id="solstice-footer-min" class="footer-min">
  <div class="container"">
    <div class="row">
     <div class="col-sm-13">
       <p>Copyright &copy; <?php print date('Y');?> The Eclipse Foundation. All Rights Reserved.</p>
     </div>
     <div class="col-sm-11">
       <ul class="list-inline" id="footer-legal-links">
         <li><a href="http://www.eclipse.org/legal/privacy.php">Privacy Policy</a></li>
         <li><a href="http://www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
         <li><a href="http://www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
       </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php print $this->getThemeUrl('solstice')?>public/javascript/main.min.js"></script>
<?php print $this->getExtraJsFooter();?>
</body>
</html>
