<?php
defined('APP_ENV') || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

// error reporting
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
require_once 'vendor/autoload.php';

use Bundler\JavascriptMarkup;
use Bundler\StylesheetMarkup;

$stylesheetMarkup = new StylesheetMarkup();

// optional, path relative to yaml, used by development mode
// default shown
$stylesheetMarkup->setYaml('.bundler/stylesheet.yaml');

// optional host
// default shown
$stylesheetMarkup->setHost('');

// optional, path relative to public stylesheet
// default shown
$stylesheetMarkup->setPublic('public/css');

// optional output minified files
// default shown
$stylesheetMarkup->setMinified(true);

// optional, in development mode, output each file
// default shown
$stylesheetMarkup->setDevelopment(true);

// markup for package package-yuicompressor
// default shown
echo $stylesheetMarkup->get('package-yuicompressor');
echo PHP_EOL;



$javascriptMarkup = new JavascriptMarkup();

// optional, path relative to yaml, used by development mode
// default shown
$javascriptMarkup->setYaml('.bundler/javascript.yaml');

// optional host
// default shown
$javascriptMarkup->setHost('');

// optional, path relative to public javascript
// default shown
$javascriptMarkup->setPublic('public/js');

// optional, output minified files
// default shown
$javascriptMarkup->setMinified(true);

// optional, in development mode, output each file
// default shown
$javascriptMarkup->setDevelopment(true);

// markup for package package-google-closure-compiler
// default shown
echo $javascriptMarkup->get('package-google-closure-compiler');

// markup for package package-yuicompressor
// default shown
echo $javascriptMarkup->get('package-yuicompressor');
echo PHP_EOL;