<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Advanced Options</a>
    </li>
  </ul>
</div>

<?php
openBox("Advanced Tools", "cog", 12);
?>
<h3>Advanced Features and Options</h3>
<p>This page is a collection of advanced features and options that you can use to tweak your PHP Psuedo Compiler just the way you like it.</p>
<p> The following tools and the associated options are available in this advanced section of the Pro version of this program. </p>

<ul>
  <li><b>Suppress Duplicates</b>: By default, PHP Pseudo Compiler lists and checks all instances of functions used or not found. You can suppress duplications using this option, which may make the output of large projects more readable.  </li>
  <li><b>Execution Time</b>: If you have large plugins or applications to validate, you may run out of time. If that happens, you may increase the execution time limit here. The number is in seconds. Typical value is 30s.</li>
  <li><b>Memory Limit</b></li>: If you have large plugins or applications to validate, you may run out of memory. If that happens, you may increase the memory limit here. The number is in MB. Typical value is 128. You may give up to 2048, depending on your system.
  <li><b>Dynamic Analysis</b>:The uploaded files are parsed and examined statically by default. If you would like to do dynamic analysis by executing the files, please check here. <span class="red">Please note that executing uploaded files may have side effects, and it may be a security hole as well. For that reason, this Pro option requires double-confirmation and self-destrucs after execution.</li>
  <li><b>Show All Tokens</b>:For debugging purposes, you may want to see all tokens. If so, enable this option. <span class="red">It will force this app to exit after printing out all the tokens from the first file it process, without doing anything else.</span></li>
  <li><b>Enable Breadcrumbs</b>:On PHP Psuedo Compiler admin page, you can have breadcrumbs so that you can see where you are. This feature is of questionable value on an admin page, and is disabled by default.</li>
  <li><b>Menu Placement</b>:By default, PHP Psuedo Compiler automatically places the navigation menu on the left side of the screen in standalone mode, and at the top of the screen in WordPress plugin mode. Using this option, you can force the placement either to Top or Left, or leave at as the default Auto mode.</li>
  <li><b>Select Theme</b>:If you are not crazy about the default color scheme of PHP Pseudo Compiler, you can change it here. After changing the theme, the page will update automatically. If it does not, please click on the Switch Theme button.</li>
</ul>
<hr>
<h4>Screenshot of Advanced Options from the <a href="#" class="goPro">Pro</a> Version</h4>
<?php
showScreenshot(5);
?>
<div class="clearfix"></div>
<?php
closeBox();
require 'promo.php';
require 'footer.php';
