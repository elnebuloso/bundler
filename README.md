# bundler

[![Build Status](https://img.shields.io/travis/elnebuloso/bundler/master.svg?style=flat-square)](https://travis-ci.org/elnebuloso/bundler)
[![Software License](https://img.shields.io/packagist/l/elnebuloso/bundler.svg?style=flat-square)](LICENSE)

## About

- Bundler minifies your Javascript
- Bundler minifies your Stylesheet
- Bundler packages your Project

## Installation / Usage

Global Project Installation

```
composer create-project elnebuloso/bundler /path/to/your/bundler-installation
```

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

- PHP 5.3
- PHP 5.4
- PHP 5.5
- PHP 5.6
- HHVM

## usage

- create folder .bundler in your project root
- create file .bundler/files.yaml for bundling the project
- create file .bundler/stylesheet.yaml for bundling stylesheets
- create file .bundler/javascript.yaml for bundling javascripts

### examples

see demo yaml files under ./bundler

- documentation of each setting can be found in the demo yaml files
- all include / exclude definitions are regular expressions
- compilers are stackable, use any compiler that works with input / output file

### commands (called in project root)

- /path/to/your/bundler-installation/bin/bundler
- /path/to/your/bundler-installation/bin/bundler files [--help]
- /path/to/your/bundler-installation/bin/bundler stylesheet [--help]
- /path/to/your/bundler-installation/bin/bundler javascript [--help]

## rendering markup (examples)

- see public/index.php for examples

``` php
$stylesheetMarkup = new Bundler\Markup\StylesheetMarkup();
$stylesheetMarkup->setDevelopment(true);
$stylesheetMarkup->setMinified(true);
$stylesheetMarkup->setVersionized(true);

echo $stylesheetMarkup->getMarkup('stylesheetFoo');
```

``` php
$javascriptMarkup = new Bundler\Markup\JavascriptMarkup();
$javascriptMarkup->setDevelopment(true);
$javascriptMarkup->setMinified(true);
$javascriptMarkup->setVersionized(true);

echo $javascriptMarkup->getMarkup('javascriptFoo');
```