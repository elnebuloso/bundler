# bundler

[![Build Status](https://travis-ci.org/elnebuloso/bundler.svg?branch=master)](https://travis-ci.org/elnebuloso/bundler)
[![License](https://poser.pugx.org/elnebuloso/bundler/license.svg)](https://packagist.org/packages/elnebuloso/bundler)

bundle your project files, your stylesheets and your javascripts

## usage

 * create folder .bundler in your project root
 * create file .bundler/files.yaml for bundling the project
 * create file .bundler/stylesheet.yaml for bundling stylesheets
 * create file .bundler/javascript.yaml for bundling javascripts

### usage

see demo yaml files under ./bundler
all include / exclude definitions are regular expressions

## commands (used as composer package)

 * ./vendor/bin/bundler
 * ./vendor/bin/bundler bundle:files [--help]
 * ./vendor/bin/bundler bundle:stylesheet [--help]
 * ./vendor/bin/bundler bundle:javascript [--help]