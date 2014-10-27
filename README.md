# bundler

[![Build Status](https://img.shields.io/travis/elnebuloso/bundler/master.svg?style=flat-square)](https://travis-ci.org/elnebuloso/bundler)
[![Software License](https://img.shields.io/packagist/l/elnebuloso/bundler.svg?style=flat-square)](LICENSE)

## Installation / Usage

Via Composer

``` json
{
    "require-dev": {
        "elnebuloso/bundler": "~7.1"
    }
}
```

## Requirements

The following versions of PHP are supported by this version.

* PHP 5.3
* PHP 5.4
* PHP 5.5
* PHP 5.6
* HHVM

## usage

 * create folder .bundler in your project root
 * create file .bundler/files.yaml for bundling the project
 * create file .bundler/stylesheet.yaml for bundling stylesheets
 * create file .bundler/javascript.yaml for bundling javascripts

### usage

see demo yaml files under ./bundler

 * documentation of each setting can be found in the demo yaml files
 * all include / exclude definitions are regular expressions
 * compilers are stackable, use any compiler that works with input / output file

### commands (used as composer package)

 * ./vendor/bin/bundler
 * ./vendor/bin/bundler files [--help]
 * ./vendor/bin/bundler stylesheet [--help]
 * ./vendor/bin/bundler javascript [--help]