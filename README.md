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

### .bundler/files.yaml

In this demo case, this creates

 * ./build/foo/public
 * ./build/foo/src
 * ./build/foo/vendor
 * ./build/bar/src
 * ./build/bar/vendor

```
# relative to the root from which the files are collected
folder: .

# relative to the root where the files are copied
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