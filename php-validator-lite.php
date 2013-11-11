<?php
/*
Plugin Name: PHP Validator
Plugin URI: http://www.thulasidas.com/plugins/php-validator
Description: Pseudo-complier for PHP source code -- lets you detect undefined functions and methods. Access it in the Tools menu by clicking <a href="tools.php?page=php-validator/php-validator.php">Tools &rarr; PHP Validator</a>.
Version: 1.10
Author: Manoj Thulasidas
Author URI: http://www.thulasidas.com
*/

/*
Copyright (C) 2008 www.thulasidas.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

ini_set( 'error_reporting', E_ERROR );
if (!class_exists("phpValidator")) {
  class phpValidator {
    var $plgDir, $URL ;
    function phpValidator() { //constructor
      $this->dirName = dirname (__FILE__) ;
      $baseName = basename($this->dirName) ;
      $this->URL =  get_option('siteurl') . '/' . PLUGINDIR . '/' . $baseName ;
    }

    static function validToken($token) {
      $validCodes = array(T_INCLUDE, T_INCLUDE_ONCE,
                    T_REQUIRE, T_REQUIRE_ONCE, T_CLASS, T_NEW,
                    T_DOUBLE_COLON, T_OBJECT_OPERATOR,  T_FUNCTION,
                    T_STRING, T_CONSTANT_ENCAPSED_STRING) ;
      $valid = false ;
      if (is_array($token) && in_array($token[0], $validCodes)) $valid = true ;
      else if ($token == '(' || $token == ')') $valid = true ;
      return $valid ;
    }
    function printAdminPage() {
      include($this->dirName.'/htmlHelper.php') ;

      $html = new htmlHelper() ;
      $myTools = "<a href='http://buy.thulasidas.com'>Tools and Plugins for PHP, WordPress and Mac</a>" ;
      if (empty($_POST)) {
        $html->ezppHeader("Validate PHP files", $myTools, $this->URL) ;
      }
      else {
        $showTokens = $_POST['showTokens'] ;
        $showOutput = $_POST['showOutput'] ;
        $showEval = $_POST['showEval'] ;
        if (!empty($_POST['sources']))
          $sources = explode(',', $_POST['sources']) ;
        else
          $html->setErr("Error in source file list") ;
        $includePaths = explode(',', $_POST['includes']) ;

        $html->ezppHeader("Validate PHP files", $myTools, $this->URL) ;
        if (!$html->inError()) {
          $source = '' ;
          foreach ($sources as $file) $source .= file_get_contents($this->dirName . '/' . trim($file));
          $tokens = token_get_all($source);
          $tokens = array_filter($tokens, array("phpValidator", "validToken")) ;
          $handlers = array("method", "function", "include", "class", "defined") ;
          $excluded = array("true", "TRUE", "false", "FALSE", "__construct") ;
          foreach ($handlers as $h) {
            $$h = array() ;
          }
          $class[] = 'htmlHelper' ;
          $not_function = false ;
          echo "<pre>\n" ;
          $keys = array_keys($tokens) ;
          for ($i = 0; $i < count($keys); $i++) {
            $token = $tokens[$keys[$i]];
            $next = $tokens[$keys[$i+1]];
            $prev = $tokens[$keys[$i-1]];
            $pprev = $tokens[$keys[$i-2]];
            list($id, $text) = $token;
            if (!empty($text) && !in_array($text, $excluded) &&
              ($id == T_STRING || $id == T_CONSTANT_ENCAPSED_STRING)) {
              if ($showTokens) printf("%s\n%s\n", token_name($id), print_r($token, true)) ;
              if ($prev[0] == T_INCLUDE || $prev[0] == T_INCLUDE_ONCE ||
                $prev[0] == T_REQUIRE || $prev[0] == T_REQUIRE_ONCE ||
                $pprev[0] == T_INCLUDE || $pprev[0] == T_INCLUDE_ONCE ||
                $pprev[0] == T_REQUIRE || $pprev[0] == T_REQUIRE_ONCE)
                $include[] = trim($text,"\"'") ;
              if ($prev[0] == T_CLASS || $prev[0] == T_NEW) {
                $class[] = $text ;
                $defined[] = $text ;
              }
              if ($prev[0] == T_DOUBLE_COLON || $prev[0] == T_OBJECT_OPERATOR) {
                if ($next == '(' && $id == T_STRING) $method[] = $text ;
              }
              else if ($next == '(' && $id == T_STRING)
                $function[] = $text ;
              if ($prev[0] == T_FUNCTION)
                $defined[] = $text ;
            }
          }
          foreach ($handlers as $h) $$h = array_unique($$h) ;
          if ($showOutput)
            foreach ($handlers as $h) {
              echo "Type: <b>$h</b>\n" ;
              $temp = array() ;
              foreach ($$h as $h2) if (!in_array($h2, $temp)) $temp[] = $h2 ;
              sort($temp) ;
              print_r($temp) ;
              echo "</br>" ;
            }

          $testPHP .=
            "set_include_path(get_include_path() . PATH_SEPARATOR . '{$this->dirName}' " ;
          foreach ($includePaths as $i) {
            $iPath = trim($i) ;
            $testPHP .= " . PATH_SEPARATOR . '{$this->dirName}/$iPath/'" ;
          }
          $testPHP .= "); \n" ;
          $toInclude = $include ;
          $classes = get_declared_classes();
          $testPHP .= "ob_start() ;\n" ;
          if ($_POST['includeSelf']) {
            foreach ($sources as $s) $testPHP .= "include_once '{$this->dirName}/$s' ;\n" ;
          }
          if (!empty($_POST['extras'])) {
            $extras = explode(',', $_POST['extras']) ;
            foreach ($extras as $e) {
              $e = trim($e) ;
              $testPHP .= "include_once '$e' ;\n" ;
              include_once "$e" ;
            }
          }
          $testPHP .= "ob_end_clean() ;\n" ;
          $includedClasses = array_diff(get_declared_classes(), $classes);
          foreach ($toInclude as $l=>$i)
            $testPHP .= "include_once '$i' ;\n" ;
          foreach ($function as $l=>$f) {
            if (in_array($f, $defined)) {
              $testPHP .= "echo '<font color=blue>Locally defined function (or constructor) found: $f</font><br />' ;\n" ;
              continue ;
            }
            $testPHP .= "if (!function_exists('$f')) echo '<font color=red>Funciton not found: $f</font><br />' ;\n" ;
            if ($_POST['showOutput']) $testPHP .= "else {\n echo '<font color=green>Funtion found $f</font><br />' ;\n }\n" ;
          }
          foreach ($method as $l=>$m) {
            $testPHP .= "\$methodExists = false ;\n" ;
            foreach ($class as $c) {
              $testPHP .= "if (method_exists('$c', '$m')) {\n echo '<font color=green>Method found $c::$m</font><br />' ;\n \$methodExists = true ; }\n" ;
            }
            foreach ($includedClasses as $c) {
              $testPHP .= "if (method_exists('$c', '$m')) {\n echo '<font color=blue>Local method found $c::$m</font><br />' ;\n \$methodExists = true ; }\n" ;
            }
            $testPHP .= "if (!\$methodExists) echo '<font color=red>Method not found: $m</font><br />' ;\n" ;
          }
          if ($showEval) {
            echo htmlentities(print_r($testPHP, true)) . "<br />\n" ;
            eval($testPHP) ;
          }
          else @eval($testPHP) ;
          echo "</pre>\n" ;
        }
      }
      $sourcePrompt = "List your php source filess here (comma separated).<br /><small>Giving multiple source files will make the line numbers meaningless.</small>" ;
      $extraPrompt = "<br />Enter extra include file(s), if any, (comma separated)<br><small>If you don't have any additional include files, you can leave it blank.</small>" ;
      $includePrompt = "<br />List additional include paths (comma separated).<br><small>If you don't have any include paths, you can enter '.' or leave it blank.</small>" ;

      printf("<form name='form1' action='{$_SERVER['REQUEST_URI']}' method='post'>") ;
      printf("$sourcePrompt<br /><textarea rows='1' cols='95' name='sources' id='sources'>{$_POST['sources']}</textarea><br />") ;
      printf("$extraPrompt<br /><textarea rows='1' cols='95' name='extras' id='extras'>{$_POST['extras']}</textarea><br />") ;
      printf("$includePrompt<br /><textarea rows='1' cols='95' name='includes' id='includes'>{$_POST['includes']}</textarea><br /><br \>") ;
      if (empty($_POST['includeSelf'])) $checked = '' ;
      else $checked = "checked='checked'" ;
      printf("<input type='checkbox' $checked name='includeSelf'> &nbsp; Include the files in the autogenerated code? Check it if the file being validated is an include file with class definitions. If it generates HTML output, it will probably interfere with the validation output.<br \>") ;
      if (empty($_POST['showOutput'])) $checked = '' ;
      else $checked = "checked='checked'" ;
      printf("<input type='checkbox' $checked name='showOutput'> &nbsp; Show methods, functions, includes etc. that are detected and validated. Validated methods are always listed.<br \>") ;
      if (empty($_POST['showEval'])) $checked = '' ;
      else $checked = "checked='checked'" ;
      printf("<input type='checkbox' $checked name='showEval'> &nbsp; Show the autogenerated code used for validation. [For debug purposes.]<br />") ;
      if (empty($_POST['showTokens'])) $checked = '' ;
      else $checked = "checked='checked'" ;
      printf("<input type='checkbox' $checked name='showTokens'> &nbsp; Show tokens. [Will be very verbose.]<br \>") ;
      printf("\n<div align='center'><input type='submit' name='save' class='button'></div><br /></div></form>") ;
      printf("</form>") ;
      $html->ezppFooter() ;
    }
  } // End Class phpValidator
}
if (class_exists("phpValidator")) {
  $phpValidator = new phpValidator() ;
  if (isset($phpValidator)) {
    add_action('admin_init', 'phpValidator_admin_init') ;
    add_action('admin_menu', 'phpValidator_admin_menu') ;
    function phpValidator_admin_init() {
      global $phpValidator ;
      wp_register_style('phpValidatorCSS', "{$phpValidator->dirName}/ezpp.css") ;
    }
    function phpValidator_admin_menu() {
      global $phpValidator ;
      $page = add_submenu_page('tools.php','PHP Validator', 'PHP Validator',
              "install_plugins", __FILE__, array($phpValidator, 'printAdminPage')) ;
      add_action('admin_print_styles-' . $page, 'phpValAdminStyles') ;
    }
    function phpValAdminStyles() {
      wp_enqueue_style('phpValidatorCSS') ;
    }
  }
}
?>
