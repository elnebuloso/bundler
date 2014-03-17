![Image](logo.png?raw=true)

bundler
=======

[![Latest Stable Version](https://poser.pugx.org/elnebuloso/bundler/v/stable.png)](https://packagist.org/packages/elnebuloso/bundler) [![Total Downloads](https://poser.pugx.org/elnebuloso/bundler/downloads.png)](https://packagist.org/packages/elnebuloso/bundler) [![Latest Unstable Version](https://poser.pugx.org/elnebuloso/bundler/v/unstable.png)](https://packagist.org/packages/elnebuloso/bundler) [![License](https://poser.pugx.org/elnebuloso/bundler/license.png)](https://packagist.org/packages/elnebuloso/bundler)

 * bundling files 'n stuff

usage
=====

 * create folder .bundler in your project root
 * create file .bundler/files.php for bundling the whole project
 * create file .bundler/stylesheet.php for bundling stylesheets
 * create file .bundler/javascript.php for bundling javascripts

commands
========
 * ./vendor/bin/bundler.php
 * ./vendor/bin/bundler.php bundle:files
 * ./vendor/bin/bundler.php bundle:stylesheet
 * ./vendor/bin/bundler.php bundle:javascript

demo .bundler/files.php
============================

 * folder: relative to the root from which the files are collected
 * target: relative to the root where the files are copied
 * bundle/[package]
  * define multiple packages here
  * in this demo case, this creates
   * ./build/foo/public
   * ./build/foo/src
   * ./build/foo/vendor
   * ./build/bar/src
   * ./build/bar/vendor

```php
<?php
<?php
return array(
    "folder" => ".",
    "target" => "build",
    "bundle" => array(
        "foo" => array(
            "include" => array(
                "public/.*",
                "src/.*",
                "vendor/.*"
            ),
            "exclude" => array("^.+README.md")
        ),
        "bar" => array(
            "include" => array(
                "src/.*",
                "vendor/.*"
            ),
            "exclude" => array("^.+README.md")
        )
    )
);
```

demo .bundler/stylesheet.php
============================

 * folder: relative to the root from which the files are collected
 * target: relative to the root where the files are copied
 * bundle/[package]
  * define multiple packages here
  * in this demo case, this creates ./public/css/foo.bundler.css
  * in this demo case, this creates ./public/css/foo.bundler.min.css
 * all paths in the stylesheets are solved

```php
<?php
return array(
    "folder" => "public",
    "target" => "public/css",
    "bundle" => array(
        "foo" => array(
            "include" => array(
                "public/vendor/twitter/bootstrap/3.1.0/css/bootstrap.css",
                "public/vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css",
                "public/vendor/twitter/bootstrap/3.1.0/css/dashboard.css"
            ),
            "exclude" => array()
        )
    )
);
```

demo .bundler/javascript.php
============================

 * folder: relative to the root from which the files are collected
 * target: relative to the root where the files are copied
 * bundle/[package]
  * define multiple packages here
  * in this demo case, this creates foo.bundler.js
  * in this demo case, this creates foo.bundler.min.js
  * in this demo case, this creates bar.bundler.js
  * in this demo case, this creates bar.bundler.min.js

```php
<?php
return array(
    "folder" => "public",
    "target" => "public/js",
    "bundle" => array(
        "foo" => array(
            "include" => array(
                "public/vendor/afarkas/html5shiv/3.7.0/src/html5shiv.js",
                "public/vendor/scottjehl/respond/1.4.2/respond.src.js"
            ),
            "exclude" => array()
        ),
        "bar" => array(
            "include" => array(
                "public/vendor/twitter/bootstrap/3.1.0/js/bootstrap.js",
                "public/vendor/jquery/jquery/1.11.0/jquery-1.11.0.js"
            ),
            "exclude" => array()
        )
    )
);
```