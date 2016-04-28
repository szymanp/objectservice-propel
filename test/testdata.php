<?php
require_once "config.php";

use SzymanTest\Model\User;

$u = new User;
$u->setHandle("Administrator");
$u->setName("System Administrator");
$u->setEmailAddress("admin@example.org");
$u->setPassword("123456");
$u->save();

$u = new User;
$u->setHandle("sam.g");
$u->setName("Sam Gamgee");
$u->setEmailAddress("sam.g@example.org");
$u->setPassword("mordor");
$u->save();

$u = new User;
$u->setHandle("frodo");
$u->setName("Frodo Baggins");
$u->setEmailAddress("frodo@example.org");
$u->setPassword("theonering");
$u->save();
