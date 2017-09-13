<?php ob_start(); ?>
<section class="highlight background-grey">
    <div class="container">
      <div>
        <div class="triangle triangle-black visible-lg"></div>

        <div class="col-md-15 highlight-content">
          <h1>Collaborative Working Groups</h1>
            <p>Eclipse <a href="/org/workinggroups/">Working Groups</a> allow for organizations to collaborate in the development
            of new innovations and solutions. The Eclipse Foundation is a great place to host new collaborations that follow best practices and are based on open source principles.</p>
            <p>Check out how you can start your own <a href="/org/workinggroups/">working group</a>. </p>

            <ul class="list-inline">

              <li>
                <a title="LocationTech will be the leading community for individuals and organizations to collaborate on commercially-friendly open source software that is location aware." href="//www.locationtech.org">
                  <img height="79" alt="LocationTech will be the leading community for individuals and organizations to collaborate on commercially-friendly open source software that is location aware." src="/home/images/solstice/locationtech-169x125.jpg">
               </a>
             </li>

            <li>
              <a title="PolarSys - Open Source tools for the development of embedded systems." href="//polarsys.org/">
                <img height="79" alt="PolarSys - Open Source tools for the development of embedded systems." src="/home/images/solstice/polarsys-172x125.jpg">
              </a>
            </li>

           <li>
             <a title="LTS - Enable organisations to collaborate when providing support and maintenance for Eclipse technologies." href="//lts.eclipse.org/">
               <img height="79" alt="LTS - Enable organisations to collaborate when providing support and maintenance for Eclipse technologies." src="/home/images/solstice/lts-116x125.jpg">
             </a>
           </li>
           <li>
             <a title="iot.eclipse.org is where you can learn about the technologies developed at Eclipse to make Internet of Things (IoT) development simpler." href="http://iot.eclipse.org">
               <img height="79" alt="iot.eclipse.org is where you can learn about the technologies developed at Eclipse to make Machine-to-Machine (M2M) development simpler." src="/home/images/solstice/ito-150x125.jpg">
             </a>
           </li>
           <li>
             <a title="Auto IWG - Open Source Initiative for Automotive Software Development Tools." href="//wiki.eclipse.org/Auto_IWG">
               <img height="79" alt="Auto IWG - Open Source Initiative for Automotive Software Development Tools." src="/home/images/solstice/automotive-155x125.jpg">
             </a>
           </li>
         </ul>
       </div>
       <div class="col-md-9 highlight-img hidden-xs hidden-sm">
         <a href="/projects/">
           <img alt="Eclipse Working groups" class="img-responsive" src="/home/images/solstice/badge-working-groups.jpg">
         </a>
       </div>
     </div>
  </div>
</section>
<?php $html = ob_get_clean();?>

<h3 id="section-highlight">Highlight</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>