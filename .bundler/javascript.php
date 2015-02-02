<?php
return array(

    # package name: stylesheetFoo
    'stylesheetFoo' => array(
        # public directory, relative to the root from which the files are collected
        'public' => 'public',

        # folder under the public directory
        'target' => 'css',

        # compiler command to compress / minify / etc. source files to destination files
        # you can stack multiple compilers which are called after another
        # %source%  placeholder for source files
        # %destination% placeholder for destination files
        'compilers' => array(
            'yuicompressor --type css --line-break 5000 -o %source% %destination%'
        ),

        # define includes (all relative under public directory)
        'include' => array(
            'vendor/twitter/bootstrap/3.1.0/css/bootstrap.css',
            'vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css'
        )
    ),

    # package name: stylesheetBar
    'stylesheetBar' => array(
        'public' => 'public',
        'target' => 'css',
        'compilers' => array(
            'yuicompressor --type css --line-break 5000 -o %source% %destination%'
        ),
        'include' => array(
            'vendor/twitter/bootstrap/3.1.0/css/bootstrap.css',
            'vendor/twitter/bootstrap/3.1.0/css/bootstrap-theme.css',
            'css/base.css'
        )
    )
);