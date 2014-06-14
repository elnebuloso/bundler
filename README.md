[![Latest Stable Version](https://poser.pugx.org/elnebuloso/bundler/v/stable.png)](https://packagist.org/packages/elnebuloso/bundler) [![Total Downloads](https://poser.pugx.org/elnebuloso/bundler/downloads.png)](https://packagist.org/packages/elnebuloso/bundler) [![Latest Unstable Version](https://poser.pugx.org/elnebuloso/bundler/v/unstable.png)](https://packagist.org/packages/elnebuloso/bundler) [![License](https://poser.pugx.org/elnebuloso/bundler/license.png)](https://packagist.org/packages/elnebuloso/bundler)

# bundler

bundle your project, your stylesheets and your javascripts

## usage

 * create folder .bundler in your project root
 * create file .bundler/files.yaml for bundling the project
 * create file .bundler/stylesheet.yaml for bundling stylesheets
 * create file .bundler/javascript.yaml for bundling javascripts

## commands (used as composer package)

 * ./vendor/bin/bundler.php
 * ./vendor/bin/bundler.php bundle:files [--help]
 * ./vendor/bin/bundler.php bundle:stylesheet [--help]
 * ./vendor/bin/bundler.php bundle:javascript [--help]

## .bundler/files.yaml

```
# path from which the files are collected
folder: .

# path where the files are copied to
target: ./build

# directory under target, if empty, no directory
# $DATETIME
# $VERSION
# $OPTION
directory: $DATETIME

# define include / exclude pattern
include:
  - src/.*
  - vendor/.*
exclude:
  - ^.+README.md
```

In this demo case, this creates the following folder structure.

 * ./build/{YmdHis}/src
 * ./build/{YmdHis}/vendor

## .bundler/files-packages.yaml

```
# path from which the files are collected
folder: .

# path where the files are copied to
target: ./build

# directory under target, if empty, no directory
# $DATETIME
# $VERSION
# $OPTION
directory: $DATETIME

# packages, define multiple packages here
bundle:

  # package foo
  foo:
    include:
      - public/.*
      - src/.*
      - vendor/.*
    exclude:
      - ^.+README.md

  # package bar
  bar:
    include:
      - src/.*
      - vendor/.*
    exclude:
      - ^.+README.md
```

In this demo case, this creates the following folder structure.

 * ./build/{YmdHis}/foo/public
 * ./build/{YmdHis}/foo/src
 * ./build/{YmdHis}/foo/vendor
 * ./build/{YmdHis}/bar/src
 * ./build/{YmdHis}/bar/vendor

## .bundler/stylesheet.yaml

```
# relative to the root from which the files are collected
folder: public

# relative to the root where the files are copied to
target: public/css

# packages, define multiple packages here
bundle:

  # package name: package-yuicompressor
  package-yuicompressor:
    compiler: yuicompressor
    include:
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap.css
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css
      - public/vendor/twitter/bootstrap/3.1.0/css/dashboard.css
```

In this demo case, this creates

 * ./public/css/package-yuicompressor.bundler.css
 * ./public/css/package-yuicompressor.bundler.min.css
 * ./public/css/package-yuicompressor.bundler.php
 * all paths in the stylesheets are solved automatically

## .bundler/javascript.yaml

```
# relative to the root from which the files are collected
folder: public

# relative to the root where the files are copied to
target: public/js

# packages, define multiple packages here
bundle:

  # package name: package-google-closure-compiler
  package-google-closure-compiler:
    compiler: google-closure-compiler
    include:
      - public/vendor/jquery/jquery/1.11.0/jquery-1.11.0.js
      - public/vendor/twitter/bootstrap/3.1.0/js/bootstrap.js

  # package name: package-yuicompressor
  package-yuicompressor:
    compiler: yuicompressor
    include:
      - public/vendor/jquery/jquery/1.11.0/jquery-1.11.0.js
      - public/vendor/twitter/bootstrap/3.1.0/js/bootstrap.js
```

In this demo case, this creates

 * ./public/js/package-google-closure-compiler.bundler.js
 * ./public/js/package-google-closure-compiler.bundler.min.js
 * ./public/js/package-google-closure-compiler.bundler.php
 * ./public/js/package-yuicompressor.bundler.js
 * ./public/js/package-yuicompressor.bundler.min.js
 * ./public/js/package-yuicompressor.bundler.min.php

The PHP Files are for the Markup Renderer.

## output stylesheet markup

```
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
```

## output javascript markup

```
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
```

