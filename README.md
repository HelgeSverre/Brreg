# Brreg

This library implements the Brønnøysundregisterene data API for Laravel.

Installation
------------

Install using composer:

    composer require anunatak/brreg

This package is compatible with Laravel 5.

Add the service provider in `config/app.php`:

    'Anunatak\Brreg\BrregServiceProvider',

And add an alias:

    'Brreg'            => 'Anunatak\Brreg\Brreg',
    
Usage
-----

