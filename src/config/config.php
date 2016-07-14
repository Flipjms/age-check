<?php

/*
 |--------------------------------------------------------------------------
 | Clumsy AgeCheck settings
 |--------------------------------------------------------------------------
 |
 |
 */

return [
    'theme'                => 'majority',
    
    'save_session'         => true,

    'success-url'          => '/',
    'fail-url'             => '/fail',

    'view'                 => 'clumsy/age-check::form',
    
    'prefix'               => 'age-check',
    'middleware'           => 'web',
    'controller-namespace' => 'Clumsy\AgeCheck\Http\Controllers',
];
