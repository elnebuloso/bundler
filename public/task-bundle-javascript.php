#!/usr/bin/env php
<?php
/** this file demonstrates how to use the bundler in another context than running the console command */

use Bundler\JavascriptBundler;

// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
/** @noinspection PhpIncludeInspection */
require_once 'vendor/autoload.php';

$writer = new Zend\Log\Writer\Stream('php://output');
$logger = new Zend\Log\Logger();
$logger->addWriter($writer);

$bundler = new JavascriptBundler('.bundler/javascript.yaml');
$bundler->getBundlerLogger()->setLogger($logger);
$bundler->bundle();