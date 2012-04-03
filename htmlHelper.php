<?php
if (class_exists("htmlHelper")) {
  echo "Problem, class htmlHelper exists! \nCannot safely continue.\n" ;
  exit ;
}
else {
  class htmlHelper {
    var $info, $warn, $err ;
    function __construct($info='', $warn='', $err='') {
      if (empty($info)) $this->info = '' ;
      else $this->info = $info ;
      if (empty($warn)) $this->warn = '' ;
      else $this->warn = $warn ;
      if (empty($err)) $this->err = '' ;
      else $this->err = $err ;
    }
    function __destruct(){
    }
    function htmlHelper($info, $warn, $err) {
      if(version_compare(PHP_VERSION,"5.0.0","<")){
        $this->__construct($info, $warn, $err);
        register_shutdown_function(array($this,"__destruct"));
      }
    }
    function setInfo($info='') {
      if (empty($info)) $this->info = '' ;
      else $this->info = $info ;
    }
    function setWarn($warn='') {
      if (empty($warn)) $this->warn = '' ;
      else $this->warn = $warn ;
    }
    function setErr($err='') {
      if (empty($err)) $this->err = '' ;
      else $this->err = $err ;
    }
    function mkActionURL($a) {
      $isPro = file_exists("pro/pro.php") ;
      $name = addslashes(htmlspecialchars($a['name'])) ;
      $help = sprintf( ' onmouseover="Tip(\'%s\', WIDTH, 300, TITLE, \'%s\', ' .
              'FIX, [this, 5, 2])" onmouseout="UnTip()" ', $a['help'], $name);
      if (!$isPro) $ret = "'http://buy.thulasidas.com/$name' onclick='return confirm(\"$name is a paid package. Would you like to purchase the $name package (\${$a['price']})?\");' target='_blank' $help" ;
      return $ret ;
    }
    function showActions($actions, $button=false) {
      $ret = '' ;
      foreach ($actions as $a) {
        $name = $a['name'] ;
        if ($button)
          $nameButton = "<input type='button' value='$name' name='$name' $style>" ;
        else
          $nameButton = $name ;
        $url = $this->mkActionURL($a) ;
        $ret .= " <a href=$url>$nameButton</a> \n" ;
      }
      return $ret ;
    }
    function ezppHeader($heading, $welcome, $pwd='.', $custom=false, $onload='') {
      $actions = array() ;
      $actions["pro"] = array(
        "name" => "PHP Validator Pro",
        "price" => "0.99",
        "help" => "The Lite version of this plugin is fully functional. It will show undefined functions and methods as the Pro version does. The Pro version adds the ability show line numbers as well, which may make editing a bit easier.") ;
      $actions["standalone"] = array(
        "name" => "phpValidator",
        "price" => "1.45",
        "help" => "This plugin is also available as a stand-alone package. The Standalone version has all the features of the Pro version, but works as a stand-alone package, independent of WordPress. You can install it on your local development box and invoke it directly from a browser to <code>compile</code> your PHP code. It will list undefined functions and methods with line numbers.") ;
      $this->info = "Upgrade PHP Validator: " . $this->showActions($actions, true) ;
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
       <div>", $welcome, $upgrade, $heading) ;
      if (!empty($this->err))
        echo "<p align='center' id='errormessage'>{$this->err}</p>" ;
      if (!empty($this->info))
        echo "<p align='center' id='infomessage'>{$this->info}</p>" ;
      if (!empty($this->warn))
        echo "<p align='center' id='warnmessage'>{$this->warn}</p>" ;
      echo '    </div>
    <div style="padding:0 3px 5px 3px;">
    <div style="padding:3px; padding-top:1px; padding-bottom:5px;">
<!-- End of ezppHeader() -->
';
    }
    function ezppFooter($custom=false) {
      printf('<!-- Start of ezppFooter() -->
    </div>
  </div>
</div>') ;
    }
    function ezDie($err, $heading='Error Exit', $welcome='Cannot safely continue',
      $pwd='.', $custom=false) {
      $this->err = $err ;
      $this->ezppHeader($heading, $welcome, $pwd, $custom) ;
      $this->ezppFooter() ;
    }
    function inError() {
      return !empty($this->err) ;
    }
    static function redirect($target, $get='') {
      if (file_exists($target) && strpos($_SERVER['PHP_SELF'], $target) === false) {
        if (!empty($get)) $get = "?$get" ;
        header("location:$target$get") ;
      }
      else {
        die ("<br />Problem locating <code>$target</code>. Please reinstall phpValidator!") ;
      }

    }
  }
}
?>