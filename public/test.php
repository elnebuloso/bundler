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
use Bundler\Compiler\GoogleClosureCompiler;
use Bundler\Compiler\YuiCompressor;

//$fileBundler = new FileBundler(dirname(__DIR__), dirname(__DIR__) . '/.bundler/files.yaml');
//$fileBundler->getPackageByName('foo')->selectFiles();
//var_dump($fileBundler->getPackageByName('foo')->getFileSelector()->getFilesCount());

//$javascriptBundler = new JavascriptBundler(dirname(__DIR__), dirname(__DIR__) . '/.bundler/javascript.yaml');
//var_dump($javascriptBundler);

//s

var_dump(time());