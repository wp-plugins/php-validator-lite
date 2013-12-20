<?php

if (class_exists("htmlHelper")) {
  echo "Problem, class htmlHelper exists! \nCannot safely continue.\n";
  exit;
}
else {

  class htmlHelper {

    var $info, $warn, $err;

    function __construct($info = '', $warn = '', $err = '') {
      if (empty($info)) {
        $this->info = '';
      }
      else {
        $this->info = $info;
      }
      if (empty($warn)) {
        $this->warn = '';
      }
      else {
        $this->warn = $warn;
      }
      if (empty($err)) {
        $this->err = '';
      }
      else {
        $this->err = $err;
      }
    }

    function __destruct() {

    }

    function htmlHelper($info, $warn, $err) {
      if (version_compare(PHP_VERSION, "5.0.0", "<")) {
        $this->__construct($info, $warn, $err);
        register_shutdown_function(array($this, "__destruct"));
      }
    }

    function setInfo($info = '') {
      if (empty($info)) {
        $this->info = '';
      }
      else {
        $this->info = $info;
      }
    }

    function setWarn($warn = '') {
      if (empty($warn)) {
        $this->warn = '';
      }
      else {
        $this->warn = $warn;
      }
    }

    function setErr($err = '') {
      if (empty($err)) {
        $this->err = '';
      }
      else {
        $this->err = $err;
      }
    }

    function mkActionURL($a) {
      $name = addslashes(htmlspecialchars($a['name']));
      $code = $a['code'];
      $help = htmlentities($a['help']);
      if (!empty($code)) {
        $ret = "'http://buy.thulasidas.com/$code' onclick='return confirm(\"$name is a paid package. Would you like to purchase the $name package (\${$a['price']})?\");' target='_blank' onmouseover=\"Tip('$help', WIDTH, 300, TITLE, '$name', FIX, [this, 5, 2])\" onmouseout=\"UnTip()\"";
      }
      else {
        $ret = "'#' onclick='return false;' style='float:right' onmouseover=\"TagToTip('help', WIDTH, 400, TITLE, '$name', FIX, [this, -200, 2])\" onmouseout=\"UnTip()\"";
      }
      return $ret;
    }

    function showActions($actions) {
      $ret = '';
      foreach ($actions as $a) {
        $name = $a['name'];
        if ($name == "Help") {
          $nameButton = "<span style='color:red;font-size:1.3em;text-decoration:underline'>Help</span>";
        }
        else {
          $nameButton = "<input type='button' value='$name' name='$name'>";
        }
        $url = $this->mkActionURL($a);
        $ret .= " <a href=$url>$nameButton</a> \n";
      }
      return $ret;
    }

    function ezppHeader($heading, $welcome, $pwd = '.', $custom = false, $onload = '') {
      $actions = array();
      $actions["pro"] = array(
          "name" => "PHP Validator Pro",
          "price" => "0.99",
          "code" => "php-validator",
          "help" => "The Lite version of this plugin is fully functional. It will show undefined functions and methods as the Pro version does. The Pro version adds the ability show line numbers as well, which may make editing a bit easier.");
      $actions["standalone"] = array(
          "name" => "phpValidator",
          "price" => "1.45",
          "code" => "phpValidator",
          "help" => "This plugin is also available as a stand-alone package. The Standalone version has all the features of the Pro version, but works as a stand-alone package, independent of WordPress. You can install it on your local development box and invoke it directly from a browser to <code>compile</code> your PHP code. It will list undefined functions and methods with line numbers.");
      $actions["help"] = array(
          "name" => "Help");
      $this->info = "Upgrade PHP Validator: " . $this->showActions($actions);
      printf("
<script type='text/javascript' src='$pwd/wz_tooltip.js'></script>
<div id='container'>
  <div id='ezheader'>
    <a id='logo' href='http://buy.thulasidas.com/phpValidator' title='phpValidator'><img src='$pwd/phpValidator.png' width='188' height='72' alt='Ez-PayPal'></a>
    <p id='info'>%s<br />%s</p>
  </div>
  <div id='nav'>
    <ul id='sub_nav'>
      <li>%s</li>
    </ul>
  </div>
  <div class='clear'></div>
     <div id='content'>
       <div>", $welcome, $upgrade, $heading);
      if (!empty($this->err)) {
        echo "<p align='center' id='errormessage'>{$this->err}</p>";
      }
      if (!empty($this->info)) {
        echo "<p align='center' id='infomessage'>{$this->info}</p>";
      }
      if (!empty($this->warn)) {
        echo "<p align='center' id='warnmessage'>{$this->warn}</p>";
      }
      echo '    </div>
    <div style="padding:0 3px 5px 3px;">
    <div style="padding:3px; padding-top:1px; padding-bottom:5px;">
<!-- End of ezppHeader() -->
';
    }

    function ezppFooter($custom = false) {
      printf('<!-- Start of ezppFooter() -->
    </div>
  </div>
</div>');
      echo <<<EOF
<div id="help" style="visibility:hidden">
<h4>What does this program do?</h4>

<p><em>PHP Validator</em> is a developer tool. It scans the file you specify and determines whether you have undefined functions or methods.</p>

<h4>What do I enter in "List your php source files here"?</h4>

<p>You enter the full path names of the files you would like to validate. Note that <em>PHP Validator</em> runs on a server, and the files need to be accessible by your web server. Please specify the files relative to the installation directory, or by typing in their full path names.</p>

<h4>What do I enter in "Enter extra include file(s) if any"?</h4>

<p><em>PHP Validator</em> will look for definitions of methods and functions in the files specified by traversing the include directives in them. But if you have some of the defined elsewhere, you can list them here (again, relative to the installation directory) so that they are scanned as well, for function/class defintions.</p>

<h4>What about "List additional include paths"?</h4>

<p>This is to tell <em>PHP Validator</em> that you have files included from these locations. This way, you don't have to give the fully qualified name for the include files in the "Extra include files" list above.</p>

<h4>What is the purpose of the "Include the files in autogenerated code" option?</h4>

<p>This plugin works by scanning the PHP source file you specify for functions and methods and trying to locate them in the include files (in the source file as well as the ones you specify as "Extra include file(s)". In some (or most) cases, you have some functions/methods defined in the file you validating, in which case you have to include that one also, which is what this option specifies.</p>

<h4>What is "Show methods, functions, includes etc. that are detected and validated"?</h4>

<p>By default, <em>PHP Validator</em> looks for methods and lists them in red if not found. But if you would like it to look for more tokens like functions and includes, please check this option.</p>

<h4>What is "Show the autogenerated code used for validation"?</h4>

<p><em>PHP Validator</em> works by generating fake code and executing it and capturing error messages. This option lists the generated code, and is meant for debugging purposes. You may not want to check it unless you want to see the innards of <em>PHP Validator</em>.</p>

<h4>Should I check "Show tokens"?</h4>

<p>No. This is another debug option to list all the tokens found, and will generate large output.</p>
</div>
EOF;
    }

    function ezDie($err, $heading = 'Error Exit', $welcome = 'Cannot safely continue', $pwd = '.', $custom = false) {
      $this->err = $err;
      $this->ezppHeader($heading, $welcome, $pwd, $custom);
      $this->ezppFooter();
    }

    function inError() {
      return !empty($this->err);
    }

    static function redirect($target, $get = '') {
      if (file_exists($target) && strpos($_SERVER['PHP_SELF'], $target) === false) {
        if (!empty($get)) {
          $get = "?$get";
        }
        header("location:$target$get");
      }
      else {
        die("<br />Problem locating <code>$target</code>. Please reinstall phpValidator!");
      }
    }

  }

}
