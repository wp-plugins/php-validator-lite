<?php

require_once 'DbHelper.php';
require_once 'lib/PhpFastCache.php';
$cache = new PhpFastCache();
require_once 'lib/Logger.php';
$log = new Logger();

// Suppress errors on AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  error_reporting(E_ERROR | E_PARSE);
// CORS headers
  header("access-control-allow-origin: *", true);
  header("access-control-allow-methods: GET, POST, PUT, DELETE, OPTIONS", true);
}

if (!class_exists("EZ")) {

  require 'EZCom.php';

  class EZ extends EZCom {

  }

}

EZ::$slug = 'php-validator';
EZ::$class = "PhpValidator";
EZ::$isInWP = isset($_REQUEST['wp']);
EZ::$isUpdating = isset($_REQUEST['update']);
EZ::$isPro = file_exists('options-advanced.php');

// construct DB object after defining EZ
$db = new DbHelper();

if (!EZ::$isInWP) {
  require_once 'admin/lang.php';
}

EZ::$options = EZ::getOptions(); // to prime the static variable and the cache
if (!empty(EZ::$options['salt'])) {
  EZ::$salt = EZ::$options['salt'];
}
if (!empty(EZ::$options['cache_timeout'])) {
  EZ::$cacheTimeout = EZ::$options['cache_timeout'];
}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code')) {

  function http_response_code($newcode = NULL) {
    static $code = 200;
    if ($newcode !== NULL) {
      if (!headers_sent()) {
        header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
        $code = $newcode;
      }
    }
    return $code;
  }

}
