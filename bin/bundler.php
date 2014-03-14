#!/usr/bin/env php
<?php
// error reporting
error_reporting(E_ALL);
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

// composer autoloading
if(file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

use Bundler\Command\BuildCommand;
use Bundler\Command\JavascriptCommand;
use Bundler\Command\StylesheetCommand;
use Symfony\Component\Console\Application;

$console = new Application();
$console->add(new StylesheetCommand());
$console->add(new JavascriptCommand());
$console->add(new BuildCommand());
$console->run();