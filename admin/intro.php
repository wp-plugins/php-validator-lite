<?php
if (!menuHidden()) {
  ?>
  <a href="#" class="btn btn-warning goPro" style="float:right" data-toggle="tooltip" title="Get the Pro Version Now for $15.95!"> <i class="glyphicon glyphicon-shopping-cart"></i> Buy PHP Pseudo Compiler Pro Now!</a>
  <?php
}
?>
<h2>PHP Pseudo Compiler <br>
  <small>A Pseudo Compiler for PHP.</small>
</h2>
<?php
if (menuHidden()) {
  EZ::showService();
}
?>
<p><em>PHP Pseudo Compiler</em> is a smart and useful developer tool, and a quality checker for the end user. It scans the applications and plugins you specify for undefined functions and methods.</p>

<h4>Features</h4>

<ol>
  <li>Modern Admin Interface: <em>PHP Pseudo Compiler</em> sports a modern and beautiful admin interface based on the twitter bootstrap framework.</li>
  <li>Admin Interface Tour: A slick tour will take you around the admin page and familiarize you with its features.</li>
  <li>Generous Help: Whenever you need help, the information and hint is only a click away in <em>PHP Pseudo Compiler</em>. (In fact, it is only a mouseover away.)</li>
  <li>Robust Security: Unbreakable authentication (using hash and salt), impervious to SQL injection etc.</li>
  <li>WordPress Integration: PHP Pseudo Compiler comes with built-in WordPress integration. It works as a WordPress plugin if uploaded to the <code>wp-content/plugins</code> folder of your blog. What's more, you can switch to the standalone mode from the WordPress plugin admin page of this application, while still using the WordPress authentication mechanism and database.</li>
</ol>

<p><em>PHP Pseudo Compiler</em> is available as a freely distributed <a href="http://buy.thulasidas.com/lite/php-validator-lite.zip" title="Get PHP Pseudo Compiler Lite">lite version</a> and a <a href="http://buy.thulasidas.com/php-validator" title="Get PHP Pseudo Compiler Pro for $4.95" class="goPro">Pro version</a>, which adds a couple of extra features.</p>

<h4>Pro Features</h4>

<p>If the following features are important to you, consider buying the <em>Pro</em> version.</p>

<ol>
  <li><em>Upload and Check PHP packages</em>: In the <em>Pro</em> version, you can upload a package as a zip file and check for missing function/method definitions.</li>
  <li><em>WordPress Support</em>: The <em>Pro</em> version can load and check any plugin on your server, and recognizes WordPress functions.</li>
<li><em>Skinnable Admin Interface</em>: In the <em>Pro</em> version, you can select the color schemes of your admin pages from nine different skins.</li>
<li><em>Advanced Options</em>: The <em>Pro</em> version lets you configure advanced options like suppressing duplicates, displaying all detected tokens, ability to do dynamic code analysis etc.</li>
<li><em>Advanced Options</em>: The <em>Pro</em> version lets you configure advanced options like suppressing duplicates, displaying all detected tokens, ability to do dynamic code analysis etc.</li>
<li><em>Detailed Output</em>: The <em>Pro</em> version displays line numbers where functions and methods are defined.</li>
</ol>

<h4>Limitations</h4>

<p>You will find this application carefully coded and feature rich. However, it has some inherent limitation.</p>

<ol>
  <li>When it works in the static mode (which is the default), it can only detect method invocations in the standard for like <code>self::method(), $this->method(), parent::method()</code> etc.</li>
  <li>In particular, it has no way of detecting the class of invocations of the kind <code>$object->method()</code>. In other words, it cannot figure out the <code>class</code> of <code>object</code>.</li>
  <li>When run in dynamic mode (<em>i.e.</em>, with the <em>Advanced Option</em> <strong>Dymanic Mode</strong> and the compiler flag <strong>Execute the Files</strong> are both is turned on) and the  as well as the , please be careful about the side effects of the PHP files that you are running, especially if they do database manipulations. However, WordPress plugins are usually safe to run in <strong>Dynamic Mode</strong> because they typically have side effects only on the form of hooks and filters that come alive when run within WordPress. </li>
  <li>PHP Pseudo Compiler can handle only one level of class inheritance.</li>
</ol>