<?php

require_once('../../EZ.php');
require_once '../Updater.php';

$updater = new Updater('php-validator');
$updater->name = "PHP Pseudo Compiler";
$updater->toVerify = array('php-validator.php', 'wp-php-validaor.php');

$updater->handle();
