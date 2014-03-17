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
            "exclude" => array("^.+README.md")
        )
    )
);