![Image](logo.jpg?raw=true)

bundler
=======

[![Latest Stable Version](https://poser.pugx.org/elnebuloso/bundler/v/stable.png)](https://packagist.org/packages/elnebuloso/bundler) [![Total Downloads](https://poser.pugx.org/elnebuloso/bundler/downloads.png)](https://packagist.org/packages/elnebuloso/bundler) [![Latest Unstable Version](https://poser.pugx.org/elnebuloso/bundler/v/unstable.png)](https://packagist.org/packages/elnebuloso/bundler) [![License](https://poser.pugx.org/elnebuloso/bundler/license.png)](https://packagist.org/packages/elnebuloso/bundler)

 * bundling files 'n stuff

usage
=====

 * create folder .bundler in your project root
 * create file .bundler/stylesheet.php for bundling stylesheets
 * create file .bundler/javascript.php for bundling javascripts
 * create file .bundler/build.php for bundling the whole project

commands
========
 * ./vendor/bin/bundler.php
 * ./vendor/bin/bundler.php version
 * ./vendor/bin/bundler.php bundle:stylesheet
 * ./vendor/bin/bundler.php bundle:javascript
 * ./vendor/bin/bundler.php bundle:build

demo .bundler/stylesheet.php
============================

 * folder: relative to the root from which the files are collected
 * target: relative to the root where the files are copied
 * bundle/[package]
  * define multiple packages here
  * in this demo case, this creates stylesheet.top.bundler.css
  * in this demo case, this creates stylesheet.top.bundler.min.css

```php
<?php
return array(
    "folder" => "public",
    "target" => "public/css",
    "bundle" => array(
        "stylesheet.top" => array(
            "include" => array(
                "vendor/twitter/bootstrap/3.1.0/css/bootstrap.css",
                "vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css",
                "vendor/twitter/bootstrap/3.1.0/css/dashboard.css"
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
  * in this demo case, this creates javascript.top.bundler.js
  * in this demo case, this creates javascript.top.bundler.min.js
  * in this demo case, this creates javascript.bottom.bundler.js
  * in this demo case, this creates javascript.bottom.bundler.min.js

```
<?php
return array(
    "folder" => "public",
    "target" => "public/js",
    "bundle" => array(
        "javascript.top" => array(
            "include" => array(
                "vendor/afarkas/html5shiv/3.7.0/src/html5shiv.js",
                "vendor/scottjehl/respond/1.4.2/respond.src.js"
            ),
            "exclude" => array()
        ),
        "javascript.bottom" => array(
            "include" => array(
                "vendor/twitter/bootstrap/3.1.0/js/bootstrap.js",
                "vendor/jquery/jquery/1.11.0/jquery-1.11.0.js"
            ),
            "exclude" => array()
        )
    )
);
```

demo .bundler/build.php
============================

 * folder: relative to the root from which the files are collected
 * target: relative to the root where the files are copied
 * bundle/[package]
  * define multiple packages here
  * in this demo case, this creates ./build/www/public, ./build/www/src, ./build/www/vendor,

```
<?php
return array(
    "folder" => ".",
    "target" => "build",
    "bundle" => array(
        "www" => array(
            "include" => array(
                "public/.*",
                "src/.*",
                "vendor/.*"
            ),
            "exclude" => array("./vendor/dflydev/markdown/tests/.*")
        )
    )
);
```