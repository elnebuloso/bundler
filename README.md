# bundler

[![Build Status](https://travis-ci.org/elnebuloso/bundler.svg?branch=master)](https://travis-ci.org/elnebuloso/bundler)
[![Coverage Status](https://img.shields.io/coveralls/elnebuloso/bundler.svg)](https://coveralls.io/r/elnebuloso/bundler?branch=master)
[![License](https://poser.pugx.org/elnebuloso/bundler/license.svg)](https://packagist.org/packages/elnebuloso/bundler)

bundle your project files, your stylesheets and your javascripts

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

## commands (used as composer package)

 * ./vendor/bin/bundler
 * ./vendor/bin/bundler files [--help]
 * ./vendor/bin/bundler stylesheet [--help]
 * ./vendor/bin/bundler javascript [--help]