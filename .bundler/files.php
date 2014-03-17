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