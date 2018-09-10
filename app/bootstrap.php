<?php

const ENVIROMENT = 'DEV';
require __DIR__ . '/../vendor/autoload.php';
use Tracy\Debugger;
Debugger::enable();
Debugger::$showBar = true;
$configurator = new Nette\Configurator;

$configurator->onCompile[] = function (\Nette\Config\Configurator $configurator, \Nette\DI\Compiler $compiler) {
	$compiler->addExtension('opauth', new NetteOpauth\DI\Extension());
};

//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->setDebugMode(true);
//$configurator->setDebugMode(false);
$configurator->enableTracy(__DIR__ . '/../log');
$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
//$configurator->addConfig(__DIR__ . '/config/config.local.neon');



$container = $configurator->createContainer();
// register routers
\NetteOpauth\NetteOpauth::register($container->getService('router'));


return $container;
