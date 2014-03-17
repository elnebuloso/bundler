<?php
return array(
    "folder" => "public",
    "target" => "public/css",
    "bundle" => array(
        "stylesheet.top" => array(
            "include" => array(
                "public/vendor/twitter/bootstrap/3.1.0/css/bootstrap.css",
                "public/vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css",
                "public/vendor/twitter/bootstrap/3.1.0/css/dashboard.css"
            ),
            "exclude" => array()
        )
    )
);