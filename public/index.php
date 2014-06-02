<?php
defined('APP_ENV') || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

// error reporting
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'on');

// this makes our life easier when dealing with paths.
// everything is relative to the application root now.
chdir(dirname(__DIR__));

// autoloading
include 'vendor/autoload.php';

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

// markup for package foo
// default shown
echo $stylesheetMarkup->get('foo');

// markup for package bar
echo $stylesheetMarkup->get('bar');
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

// markup for package foo
// default shown
echo $javascriptMarkup->get('foo');

// markup for package bar
echo $javascriptMarkup->get('bar');
echo PHP_EOL;