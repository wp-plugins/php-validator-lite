<?php

if (class_exists("Installer")) {
  echo "Problem, class Installer exists! \nCannot safely continue.\n";
  exit;
}
else {
  require_once 'AbstractInstaller.php';

  class Installer extends AbstractInstaller {

    function configure() {
      // Set up name, logo, extra help and tables (to verify and backup).
      $this->name = "PHP Pseudo Compiler";
      $this->logo = "img/php-validator.png";
      $this->help = "";
      $this->tables = array();
    }

    function migrate($dbBak) { // Data migration
    }

    function setup() { // Post install setup, templates definitions etc.
    }

  }

}