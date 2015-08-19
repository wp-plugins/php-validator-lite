<?php

if (!class_exists("PhpValidator")) {

  require_once 'EzPlugin.php';

  class PhpValidator extends EzPlugin {

    public function __construct($name, $key) {
      $this->plgDir = __DIR__; // cannot be in the base class!
      $this->plgURL = plugin_dir_url(__FILE__); // cannot be in the base class!
      parent::__construct();
      $this->name = $name;
      $this->key = $key;
    }

    static function install($dir = '', $mOptions = 'phpvalidator') {
      parent::install(__DIR__, 'phpvalidator');
    }

    static function uninstall($mOptions = 'phpvalidator') {
      parent::uninstall('phpvalidator');
    }

  }

} //End Class PhpValidator

if (class_exists("PhpValidator")) {
  $phpvalidator = new PhpValidator("PHP Pseudo Compiler", "phpvalidator");

  add_action('admin_menu', 'phpvalidator_admin_menu');

  if (!function_exists('phpvalidator_admin_menu')) {

    function phpvalidator_admin_menu() {
      global $phpvalidator;
      $mName = 'PHP Compiler ' . $phpvalidator->strPro;
      add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__), array($phpvalidator, 'printAdminPage'));
    }

  }

  $file = __DIR__ . '/php-validator.php';
  register_activation_hook($file, array("PhpValidator", 'install'));
  register_deactivation_hook($file, array("PhpValidator", 'uninstall'));
}

