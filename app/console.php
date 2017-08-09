<?php
require __DIR__.'/../vendor/autoload.php';

$app = new \Symfony\Component\Console\Application();
$app->add(new \creativestyle\amitools\commands\FindCommand());
$app->add(new \creativestyle\amitools\commands\GetFromLaunchConfigurationCommand());
$app->add(new \creativestyle\amitools\commands\RemoveOldAMIsCommand());
$app->run();