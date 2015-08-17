<?php

require_once('../../EZ.php');

if (!EZ::isLoggedIn()) {
  http_response_code(400);
  die("Please login before validating files!");
}

register_shutdown_function("fatal_handler");

function fatal_handler() {
  $error = error_get_last();
  if ($error !== NULL) {  // clear processing flags
    extract($error);
    if (!empty($message)) {
      $message = "File: $file Line: $line: Message: $message";
    }
    else {
      $message = "Unknown Error: Probably a PHP execution time out.";
    }
    http_response_code(400);
    die($message);
  }
}


require_once 'Tokenizer.php';

$handlers = array("methods", "functions", "includes", "classes", "defined");
$defHandlers = array("functions", "methods", "classes");
$success = $warning = $error = "";
$action = $_REQUEST['action'];
ob_start();
switch ($action) {
  case 'filelist':
    $sources = array_map('trim', explode(',', $_REQUEST['value']));
    break;
  case 'folder':
    $dir = getPath($_REQUEST['value']);
    if (is_dir($dir)) {
      $sources = findFiles($dir);
    }
    else {
      http_response_code(400);
      $error = "Specified folder $dir not found on your server.";
      die($error);
    }
    break;
  case 'file':
    http_response_code(400);
    $error = "Compiling a ZIP archive is available in the <a href='http://buy.thulasidas.com/php-validator' class='goPro'>Pro version</a> of PHP Pseudo Compilier.";
    die($error);
    break;
  case 'plugin':
    http_response_code(400);
    $error = "Validating a plugin is available in the <a href='http://buy.thulasidas.com/php-validator' class='goPro'>Pro version</a> of PHP Pseudo Compilier, when installed as a WordPress plugin.";
    die($error);
    break;
  default:
    $error = "Unknown action!";
    http_response_code(400);
    die($error);
}
$output = validate($sources);
$success = grep('Success: ', $output);
$warning = grep('Warning: ', $output);
$error = grep('Error: ', $output);

ob_end_clean();
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(array('success' => $success, 'warning' => $warning, 'error' => $error));
exit();

function validate($sources) {
  global $handlers, $defHandlers;
  foreach ($handlers as $h) {
    $$h = array();
  }
  foreach ($defHandlers as $h) {
    $defined[$h] = array();
  }
  $includePaths = array();
  foreach ($sources as $file) {
    $realFile = getPath($file);
    $result = compile($file);
    $path = dirname($realFile);
    if (!in_array($path, $includePaths)) {
      $includePaths[] = $path;
    }
    foreach ($handlers as $h) {
      $$h = array_merge_recursive($$h, $result[$h]);
    }
  }
  $testPHP = "";
  $testPHP .= "set_include_path(get_include_path()";
  foreach ($includePaths as $i) {
    $iPath = trim($i);
    $testPHP .= " . PATH_SEPARATOR . '$iPath'";
  }
  $testPHP .= "); \n";
  $output = "";
  if (EZ::$options['kill_dupes']) {
    $functions = killDupes($functions);
    $methods = killDupes($methods);
  }
  $d = "&emsp;[Static Analysis]";
  foreach ($functions as $fun) {
    $f = $fun['text'];
    $f_ = "<span style=\"min-width:150px;display:inline-block;\"><code>$f</code></span>";
    $l = "&emsp;[Called at <code>{$fun['file']}: {$fun['line']}</code>]";
    if (array_key_exists($f, $defined['functions']) || array_key_exists($f, $defined['classes'])) {
      $output .= "Success: Function found:&emsp; $f_ $l $d<br>";
    }
    else {
      $testPHP .= "if (!function_exists('$f')) \n{\n\t echo 'Error: Function not found:&emsp; $f_ $l<br>';\n}\n";
      if (!empty(EZ::$options['show_defined'])) {
        $testPHP .= "else {\n\t echo 'Success: Function found:&emsp; $f_ $l<br>';\n }\n";
      }
    }
  }
  foreach ($methods as $meth) {
    $m = $meth['text'];
    $m_ = "<span style=\"min-width:150px;display:inline-block;\"><code>$m</code></span>";
    $l = "&emsp;[Called at <code>{$meth['file']}: {$meth['line']}</code>]";
    $cm = "{$meth['class']}::$m";
    $cm_ = "<span style=\"min-width:200px;display:inline-block;\"><code>{$meth['class']}::$m</code></span>";
    $methodDefined = false;
    if (array_key_exists($cm, $defined['methods'])) {
      $output .= "Success: Method found:&emsp; $cm_ $l $d<br>";
      $methodDefined = true;
    }
    else if (!empty($defined['classes'][$meth['class']])) { //
      $clsDefined = $defined['classes'][$meth['class']];
      if (!empty($clsDefined['parent'])) {
        $cm = "{$clsDefined['parent']}::$m";
        if (array_key_exists($cm, $defined['methods'])) {
          $output .= "Success: Method found:&emsp; $cm_ $l &emsp; [Inherited from <code>$cm</code>]<br>";
          $methodDefined = true;
        }
      }
    }
    if (!$methodDefined) {
      $testPHP .= "\$methodExists = false;\n";
      $c = $meth['class'];
      $testPHP .= "if (method_exists('$c', '$m')) {\n\t \$methodExists = true; \n}\n";
      if (!empty(EZ::$options['show_defined'])) {
        $testPHP .= "if (\$methodExists) {\n\t  echo 'Success: Method found:&emsp; $cm_ $l &emsp; [Found in class <code>$c</code>]<br>';\n }\n";
      }
      $testPHP .= "else {\n\t";
      foreach ($classes as $cls) {
        $c = $cls['text'];
        $testPHP .= "if (method_exists('$c', '$m')) {\n\t \$methodExists = true; \n}\n";
      }
      $testPHP .= "if (!\$methodExists) {\n\techo 'Error: Method not found:&emsp; $cm_ $l<br>';\n}\n";
      if (!empty(EZ::$options['show_defined'])) {
        $testPHP .= "else {\n\t  echo 'Warning: Method matched:&emsp; $cm_ $l &emsp; [Possibley found in class <code>$c</code>]<br>';\n }\n";
      }
      $testPHP .= "\n}\n";
    }
  }
  if (!empty(EZ::$options['show_source'])) {
    $output .= "Warning: The auto-generated code is: <pre>" . htmlentities($testPHP) . "</pre>";
  }
  if (!empty(EZ::$options['show_tokens'])) {
    foreach ($handlers as $h) {
      $output .= "Warning: $h:<pre>" . htmlentities(print_r($$h, true)) . "</pre>";
    }
  }
  ob_start();
  eval($testPHP);
  $output .= ob_get_clean();
  return $output;
}

function grep($status, $output) {
  $lines = explode("<br>", $output);
  $found = preg_grep("/^$status/", $lines);
  $found = str_replace($status, "<br>", $found);
  return implode("", $found);
}

function compile($file) {
  $realFile = getPath($file);
  $tokenizer = Tokenizer::tokenizeFile($realFile);
  $file = pruneFile($file);
  if (!empty(EZ::$options['show_all_tokens'])) {
    $msg = "Tokens found in $realFile:<pre>";
    foreach ($tokenizer as $key => $token) {
      $type = $token->getName();
      $msg .= "<br>[$key: $type]<br>" . htmlentities(print_r($token, true));
    }
    $msg .= "</pre>";
    http_response_code(400);
    die($msg);
  }

  global $handlers, $defHandlers;
  foreach ($handlers as $h) {
    $$h = array();
  }
  foreach ($defHandlers as $h) {
    $defined[$h] = array();
  }

  $currentClass = false;
  foreach ($tokenizer as $token) {
    list($id, $text, $line, $match) = getList($token);
    $type = $token->getName();
    list($nxId, $nxText, $nxLine, $nxMatch) = getList(getNextToken($tokenizer));
    $gotNxParenthesis = $nxId == '(';
    list($nxNxId, $nxNxText, $nxNxLine, $nxNxMatch) = getList(getNxNextToken($tokenizer));
    $gotNxNxParenthesis = $nxNxId == '(';
    list($pvId, $pvText, $pvLine) = getList(getPrevToken($tokenizer));
    $key = $tokenizer->key();
    if (!empty($currentClass) && $key > $currentClass['end']) {
      $currentClass = false;
    }
    switch ($id) {
      case T_INCLUDE:
      case T_INCLUDE_ONCE:
      case T_REQUIRE:
      case T_REQUIRE_ONCE:
        $nxText = trim($nxText, "\"'");
        $includes[] = array('file' => $file, 'line' => $line,
            'text' => $nxText, 'type' => $type);
        break;
      case T_CLASS: // new class definition
        $nextBrace = getNextOpenBrace($tokenizer);
        $currentClass = array('text' => $nxText, 'line' => $line, 'file' => $file,
            'type' => $type, 'start' => $key, 'end' => $nextBrace->__get('match'));
        $defined['classes'][$currentClass['text']] = $currentClass;
      case T_NEW:
        $classes[] = array('file' => $file, 'line' => $line, 'text' => $nxText,
            'type' => $type);
        break;
      case T_IMPLEMENTS: // interface
        if (!empty($currentClass)) {
          $currentClass['interface'] = $nxText;
          $defined['classes'][$currentClass['text']] = $currentClass;
        }
        break;
      case T_EXTENDS: // inheritance
        if (!empty($currentClass)) {
          $currentClass['parent'] = $nxText;
          $defined['classes'][$currentClass['text']] = $currentClass;
        }
        break;
      case T_DOUBLE_COLON:
      case T_OBJECT_OPERATOR:
        if ($gotNxNxParenthesis) {
          if (in_array($pvText, array('$this', 'self', 'static', 'parent'))) {
            if ($pvText == 'parent' && !empty($currentClass['parent'])) {
              $className = $currentClass['parent'];
            }
            else {
              $className = $currentClass['text'];
            }
          }
          else {
            $className = $pvText;
          }
          $methods[] = array('file' => $file, 'line' => $line, 'text' => $nxText,
              'type' => $type, 'class' => $className);
        }
        break;
      case T_STRING:
        if ($gotNxParenthesis && empty($currentClass) && $pvId != T_FUNCTION && $pvId != T_DOUBLE_COLON && $pvId != T_OBJECT_OPERATOR) {
          $functions[] = array('file' => $file, 'line' => $line, 'text' => $text,
              'type' => $type);
        }
        break;
      case T_FUNCTION:
        if (!empty($currentClass)) {
          $name = "{$currentClass['text']}::$nxText";
          $defined['methods'][$name] = array('file' => $file, 'line' => $line,
              'text' => $text, 'type' => $type, 'currentClass' => $currentClass);
        }
        else {
          $name = $nxText;
          $defined['functions'][$name] = array('file' => $file, 'line' => $line,
              'text' => $text, 'type' => $type);
        }
        break;
    }
  }

  return compact($handlers);
}

function getList($token) {
  if (is_a($token, 'TokenizerToken')) {
    $type = $token->getType();
    $text = $token->__get('content');
    $line = $token->getLine();
    $match = $token->__get('match');
  }
  else {
    $type = $text = $line = $match = "";
  }
  return array($type, $text, $line, $match);
}

function getNextToken($tokenizer) {
  $currentToken = $tokenizer->key();
  $token = $tokenizer->getNextImportantToken();
  $tokenizer->setIndex($currentToken);
  return $token;
}

function getNxNextToken($tokenizer) {
  $currentToken = $tokenizer->key();
  $token = $tokenizer->getNextImportantToken();
  $token = $tokenizer->getNextImportantToken();
  $tokenizer->setIndex($currentToken);
  return $token;
}

function getPrevToken($tokenizer) {
  $currentToken = $tokenizer->key();
  $token = $tokenizer->getPreviousToken();
  $tokenizer->setIndex($currentToken);
  return $token;
}

function getNextOpenBrace($tokenizer) {
  $currentToken = $tokenizer->key();
  do {
    $token = $tokenizer->getNextToken();
  } while (!empty($token) && ($token->__get('content') != '{'));
  $tokenizer->setIndex($currentToken);
  return $token;
}

// Recursively finds all the PHP files in a folder
function findFiles($folder, $extensions = array('php')) {

  function glob_recursive($folder, &$folders = array()) {
    $dirs = glob($folder, GLOB_ONLYDIR | GLOB_NOSORT);
    if (!empty($dirs)) {
      foreach ($dirs as $folder) {
        $folders[] = $folder;
        glob_recursive("{$folder}/*", $folders);
      }
    }
  }

  glob_recursive($folder, $folders);
  $files = array();
  foreach ($folders as $folder) {
    foreach ($extensions as $extension) {
      foreach (glob("{$folder}/*.{$extension}") as $file) {
        $files[] = $file;
      }
    }
  }
  return $files;
}

function getPath($file) {
  if ($file[0] == '/') {
    $fileName = $file;
  }
  else {
    $fileName = "../../../$file";
  }
  $realFile = realpath($fileName);
  if (empty($realFile)) {
    http_response_code(400);
    die("File or folder <code>$file</code> not found on your server!");
  }
  return $realFile;
}

function pruneFile($file, $prune = "") {
  if (empty($prune)) {
    $prune = realpath(__DIR__ . "/../../../") . "/";
  }
  $file = realpath($file);
  $pruned = str_replace($prune, "", $file);
  return $pruned;
}

function killDupes($handler) {
  $ret = array();
  foreach ($handler as $v) {
    $k = $v['text'];
    if (empty($ret[$k])) {
      $ret[$k] = $v;
    }
  }
  return $ret;
}
