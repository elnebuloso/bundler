[![Latest Stable Version](https://poser.pugx.org/elnebuloso/bundler/v/stable.png)](https://packagist.org/packages/elnebuloso/bundler) [![Total Downloads](https://poser.pugx.org/elnebuloso/bundler/downloads.png)](https://packagist.org/packages/elnebuloso/bundler) [![Latest Unstable Version](https://poser.pugx.org/elnebuloso/bundler/v/unstable.png)](https://packagist.org/packages/elnebuloso/bundler) [![License](https://poser.pugx.org/elnebuloso/bundler/license.png)](https://packagist.org/packages/elnebuloso/bundler)

# bundler

bundle your project, your stylesheets and your javascripts

## usage

 * create folder .bundler in your project root
 * create file .bundler/files.yaml for bundling the project
 * create file .bundler/stylesheet.yaml for bundling stylesheets
 * create file .bundler/javascript.yaml for bundling javascripts

## commands

 * ./vendor/bin/bundler.php
 * ./vendor/bin/bundler.php bundle:files
 * ./vendor/bin/bundler.php bundle:stylesheet
 * ./vendor/bin/bundler.php bundle:javascript

## .bundler/files.yaml

```
# relative to the root from which the files are collected
folder: .

# relative to the root where the files are copied to
target: build

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

In this demo case, this creates

 * ./build/foo/public
 * ./build/foo/src
 * ./build/foo/vendor
 * ./build/bar/src
 * ./build/bar/vendor

## .bundler/stylesheet.yaml

```
# relative to the root from which the files are collected
folder: public

# relative to the root where the files are copied to
target: public/css

# packages, define multiple packages here
bundle:

  # package foo
  foo:
    include:
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap.css
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css
      - public/vendor/twitter/bootstrap/3.1.0/css/dashboard.css
    exclude:

  # package bar
  bar:
    include:
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap.css
      - public/vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css
    exclude:
```

In this demo case, this creates

 * ./public/css/foo.bundler.css
 * ./public/css/foo.bundler.min.css
 * ./public/css/bar.bundler.css
 * ./public/css/bar.bundler.min.css
 * all paths in the stylesheets are solved automatically

## .bundler/javascript.yaml

```
# relative to the root from which the files are collected
folder: public

# relative to the root where the files are copied to
target: public/js

# packages, define multiple packages here
bundle:

  # package foo
  foo:
    include:
      - public/vendor/afarkas/html5shiv/3.7.0/src/html5shiv.js
      - public/vendor/scottjehl/respond/1.4.2/respond.src.js
    exclude:

  # package bar
  bar:
    include:
      - public/vendor/twitter/bootstrap/3.1.0/js/bootstrap.js
      - public/vendor/jquery/jquery/1.11.0/jquery-1.11.0.js
    exclude:
```

In this demo case, this creates

 * ./public/js/foo.bundler.js
 * ./public/js/foo.bundler.min.js
 * ./public/js/bar.bundler.js
 * ./public/js/bar.bundler.min.js