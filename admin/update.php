<?php
require 'header.php';
require_once 'Updater.php';
$updater = new Updater('php-validator');
$updater->name = "PHP Pseudo Compiler";
$updater->price = "4.95";
$updater->render();
require 'footer.php';
