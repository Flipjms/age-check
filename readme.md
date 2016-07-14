# Age-Check
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/28eac9d3570c47009bcfa5ed8a196597)](https://www.codacy.com/app/flipjms/age-check?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Flipjms/age-check&amp;utm_campaign=Badge_Grade)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b831215-67f8-48f0-9a84-a1642c280481/mini.png)](https://insight.sensiolabs.com/projects/5b831215-67f8-48f0-9a84-a1642c280481)

A package to help create an age-check verification.

## License

Clumsy Age-Check is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Installation

To get started with Age-Check, add to your `composer.json` file as a dependency:

    composer require clumsy/age-check:0.2.*

If you are using Laravel 4, stick to the branch 0.1:

    composer require clumsy/age-check:0.1.*

## Configuration

After installing the Age-Check library, register the ServiceProvider in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    Clumsy\AgeCheck\AgeCheckServiceProvider::class,
],
```

Register the middleware in your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
        //Other middlewares...

        'age-check' => \Clumsy\AgeCheck\Http\Middleware\ValidateAge::class,
    ];
```

## Usage

Create a route group with the age-check middleware in it:

```php
Route::group(
    [
        'middleware' => ['age-check']
    ],
    function() {
        //Your routes goes here...
    }
);
```

This package gives you a basic view with a basic form without styling. You should publish the views and change them to suit your needs:

    php artisan vendor:publish --provider="Clumsy\AgeCheck\AgeCheckServiceProvider" --tag="views"

