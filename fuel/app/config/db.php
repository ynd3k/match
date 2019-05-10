<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
    'active' => 'mysqli',

    'mysqli' => array(
        'type' => 'mysqli',
        'connection' => array(
            'hostname' => 'localhost',
            'database' => 'match',
            'username' => 'root',
            'password' => 'root',
            'persistent' => false,
            'compress' => false,
        ),
        'identifier' => '`',
        'table_prefix' => '',
        'charset' => 'utf8',
        'caching' => false,
        'profiling' => true,
    ),
);
