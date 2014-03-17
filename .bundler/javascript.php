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