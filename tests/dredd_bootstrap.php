<?php

use App\Kernel;
use Dredd\Dredd;

ini_set('error_reporting', -1);
putenv('APP_ENV='.$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test');
require_once __DIR__ . '/../config/bootstrap.php';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer()->get('test.service_container');
/** @var Dredd $dredd */
$dredd = $container->get(Dredd::class);
$dredd->boot();
