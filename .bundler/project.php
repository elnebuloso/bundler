<?php
return array(
    "www" => array(
        "include" => array(
            "public/.*",
            "src/.*",
            "vendor/.*"
        ),
        "exclude" => array("vendor/dflydev/markdown/tests/.*")
    )
);