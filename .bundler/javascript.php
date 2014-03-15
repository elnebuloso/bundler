<?php
return array(
    "folder" => "public",
    "target" => "public/js",
    "bundle" => array(
        "javascript.top" => array(
            "include" => array(
                "vendor/afarkas/html5shiv/3.7.0/html5shiv.min.js",
                "vendor/scottjehl/respond/1.4.2/respond.min.js"
            ),
            "exclude" => array()
        ),
        "javascript.bottom" => array(
            "include" => array(
                "vendor/jquery/jquery/1.10.2/jquery-1.10.2.min.js"
            ),
            "exclude" => array()
        )
    )
);