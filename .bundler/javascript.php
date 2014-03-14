<?php
return array(
    "public" => "./public",
    "bundle" => array(
        "./js/javascript.top.js" => array(
            "include" => array(
                "./vendor/afarkas/html5shiv/3.7.0/html5shiv.min.js",
                "./vendor/scottjehl/respond/1.4.2/respond.min.js"
            ),
            "exclude" => array()
        ),
        "./js/javascript.bottom.js" => array(
            "include" => array(
                "./vendor/jquery/jquery/1.10.2/jquery-1.10.2.min.js"
            ),
            "exclude" => array()
        )
    )
);