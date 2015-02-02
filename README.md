# bundler

[![Build Status](https://img.shields.io/travis/elnebuloso/bundler/master.svg?style=flat-square)](https://travis-ci.org/elnebuloso/bundler)
[![Software License](https://img.shields.io/packagist/l/elnebuloso/bundler.svg?style=flat-square)](LICENSE)

## About

- Bundler minifies your Javascript
- Bundler minifies your Stylesheet

## Installation / Usage

Global Project Installation

```
composer create-project elnebuloso/bundler /path/to/your/bundler-installation
```

Via Composer

``` json
{
    "require-dev": {
        "elnebuloso/bundler": "~8.0"
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
- create file .bundler/stylesheet.php for bundling stylesheets
- create file .bundler/javascript.php for bundling javascripts

### examples

see demo files under ./bundler

- documentation of each setting can be found in the demo files
- compilers are stackable, use any compiler that works with input / output file

### commands (called in project root)

- /path/to/your/bundler-installation/bin/bundler
- /path/to/your/bundler-installation/bin/bundler stylesheet [--help]
- /path/to/your/bundler-installation/bin/bundler javascript [--help]