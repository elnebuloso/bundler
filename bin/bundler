#!/usr/bin/env php
<?php
// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// check root folder
$root = null;
$vendor = null;

// bundler as vendor
if(file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php')) {
    $root = dirname(dirname(dirname(dirname(__DIR__))));
    $vendor = $root . '/vendor/elnebuloso/bundler';
}

// bundler itself
elseif(file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    $root = dirname(__DIR__);
    $vendor = $root;
}

if(is_null($root)) {
    exit('unable to determine root folder' . PHP_EOL);
}

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir($root);

// autoloading
require_once 'vendor/autoload.php';

use Bundler\Command\FileCommand;
use Bundler\Command\JavascriptCommand;
use Bundler\Command\StylesheetCommand;
use Symfony\Component\Console\Application;

$console = new Application();

$command = new FileCommand();
$command->setRoot($root);
$console->add($command);

$command = new StylesheetCommand();
$command->setRoot($root);
$console->add($command);

$command = new JavascriptCommand();
$command->setRoot($root);
$console->add($command);

$console->run();