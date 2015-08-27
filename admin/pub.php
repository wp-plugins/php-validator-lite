<?php
$no_visible_elements = true;
require_once('header.php');
?>
<div class="row">
  <div class="col-md5 center">
    <h2 class="col-md5"><img alt="PHP Pseudo Compiler Logo" src="img/php-validator.png" style="max-width: 150px;border: 2px solid #70C7B7"/><br /><br />
      Welcome to PHP Pseudo Compiler</h2><br /><br />
  </div>
  <!--/span-->
</div><!--/row-->
<?php
openBox("Welcome to PHP Pseudo Compiler", "glass", 12);
include('intro.php');
closeBox();
openBox("<a href='http://buy.thulasidas.com/php-validator' class='goPro'>Get Your Own Ad Server Now!</a>", "shopping-cart", 12);
if (!menuHidden()) {
  ?>
  <a href="index.php" class="btn btn-success launch" style="float:right" data-toggle="tooltip" title="Launch the installer now"> <i class="glyphicon glyphicon-cog"></i> Admin / Setup</a>
  <?php
}
?>
<h3>Installation</h3>
<h4>Installing the package is simple</h4>
<ol>
  <li>First, upload the contents of the zip archive you <a href='http://buy.thulasidas.com/lite/php-validator-lite.zip'>downloaded</a> or <a class='goPro' href='http://buy.thulasidas.com/php-validator'>purchased</a> to your server. (Given that you are reading this page, you have probably already completed this step.)</li>
  <li><a href="index.php">Launch the installer</a> by visiting the admin interface using your web browser.
  </li>
  <li>Enter the DB details and set up and Admin account in a couple of minutes and you are done with the installation!</li>
</ol>

<p>Note that in the second step, your web server will try to create a configuration file where you uploaded the <code>php-validator</code> package. If it cannot do that because of permission issues, you will have to create an empty file <code>dbCfg.php</code> and make it writeable. Don't worry, the setup will prompt you for it with detailed instructions.</p>

<h4>Upgrading to Pro</h4>
<p>If you would like to have the Pro features, purchase the <a class="goPro" href='http://buy.thulasidas.com/php-validator'>Pro version</a> for $4.95. You will get an instant download link, and painless upgrade path with all your settings intact, including your admin credentials.</p>


<?php
closeBox();
require 'promo.php';
require 'footer.php';
