<?php
return array(

    # package name: javascriptFoo
    'javascriptFoo' => array(
        # public directory, relative to the root from which the files are collected
        'public' => 'public',

        # folder under the public directory
        'target' => 'js',

        # compiler command to compress / minify / etc. source files to destination files
        # you can stack multiple compilers which are called after another
        # %source%  placeholder for source files
        # %destination% placeholder for destination files
        'compilers' => array(
            'closure-compiler --compilation_level=WHITESPACE_ONLY --warning_level=QUIET --js=%source% --js_output_file=%destination%'
        ),

        # define includes (all relative under public directory)
        'include' => array(
            'vendor/jquery/jquery/1.11.0/jquery-1.11.0.js',
            'vendor/twitter/bootstrap/3.1.0/js/bootstrap.js'
        )
    ),

    # package name: javascriptBar
    'javascriptBar' => array(
        'public' => 'public',
        'target' => 'js',
        'compilers' => array(
            'closure-compiler --compilation_level=WHITESPACE_ONLY --warning_level=QUIET --js=%source% --js_output_file=%destination%'
        ),
        'include' => array(
            'vendor/jquery/jquery/1.11.0/jquery-1.11.0.js',
            'vendor/twitter/bootstrap/3.1.0/js/bootstrap.js',
            'js/document.ready.js'
        )
    )
);