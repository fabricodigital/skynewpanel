<?php
return [
    // Directories to search in.
    'directories'=> [
        'app',
        'resources',
        'config'
    ],

    // File Patterns to search for.
    'patterns'=> [
        '*.php',
        '*.js',
        '*.vue',
    ],

    // Translation function names.
    // If your function name contains $ escape it using \$
    'functions'=> [
        '__',
        '@lang',
        '_t',
   ]
];
