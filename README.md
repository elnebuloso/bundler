# bundler

[![Build Status](https://travis-ci.org/elnebuloso/bundler.svg?branch=master)](https://travis-ci.org/elnebuloso/bundler)

## About

- Bundler concatenates, minimizes your Javascript
- Bundler concatenates, minimizes your Stylesheet

## Requirements

The following versions of PHP are supported by this version.

* PHP 5.4
* PHP 5.5
* PHP 5.6
* PHP 7.0
* HHVM

## Coding Standards

Bundler follows the standards defined in the PSR-0, PSR-1, PSR-2 and PSR-4 documents.

## Installation / Usage

Global Project Installation

```
composer create-project elnebuloso/bundler /path/to/your/bundler-installation
```

Via Composer

``` json
{
    "require-dev": {
        "elnebuloso/bundler": "~9.0"
    }
}
```

## usage

- create folder .bundler in your project root
- create file .bundler/stylesheet.php for bundling stylesheets
- create file .bundler/javascript.php for bundling javascripts

### examples

see demo files under ./bundler

- documentation of each setting can be found in the demo files
- compilers are stackable, use any compiler that works with input / output file

### commands (called in project root)

- vendor/bin/bundler
- vendor/bin/bundler stylesheet [--help]
- vendor/bin/bundler javascript [--help]