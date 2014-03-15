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