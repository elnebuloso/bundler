<?php
// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
require_once 'vendor/autoload.php';

use Bundler\FileBundler;
use Bundler\JavascriptBundler;
use Bundler\StylesheetBundler;

$writer = new Zend\Log\Writer\Stream('php://output');
$logger = new Zend\Log\Logger();
$logger->addWriter($writer);

$bundler = new FileBundler('.bundler/files.yaml');
$bundler->setLogger($logger);
$bundler->configure();
$bundler->bundle();


//$bundler = new JavascriptBundler('.bundler/javascript.yaml');
//$bundler->setLogger($logger);
//$bundler->configure();
//var_dump($bundler);
//var_dump($bundler->getPackageByName('javascriptFoo'));

//$bundler = new StylesheetBundler('.bundler/stylesheet.yaml');
//$bundler->setLogger($logger);
//$bundler->configure();
//var_dump($bundler);
//var_dump($bundler->getPackageByName('stylesheetFoo'));