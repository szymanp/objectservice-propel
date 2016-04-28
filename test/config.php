<?php

$BASE_PATH = realpath(dirname(__FILE__));

$autoloader = require "vendor/autoload.php";
$autoloader->add("SzymanTest", __DIR__ . '/src');

require_once "test/propel/conf/config.php";
@include_once "config-local.php";

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
