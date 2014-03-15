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