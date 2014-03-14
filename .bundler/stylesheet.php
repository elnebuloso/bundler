<?php
return array(
    "public" => "./public",
    "bundle" => array(
        "./css/stylesheet.top.css" => array(
            "include" => array(
                "./vendor/twitter/bootstrap/3.1.0/css/bootstrap.min.css",
                "./vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.min.css",
                "./vendor/twitter/bootstrap/3.1.0/css/dashboard.css"
            ),
            "exclude" => array()
        )
    )
);