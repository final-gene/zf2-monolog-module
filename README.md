# Monolog Module
Monolog module for Zendframework 2

## Installation
Installation of this module uses composer.

```
$ composer require final-gene/monolog-module
```

Then add `FinalGene\MonologModule` to your application configuration.

## Configuration
The monolog settings can be placed in the autoload directory of your application.   
The following example represents a possible configuration.

```php
<?php
return [
    'monolog' => [

        // Error handler
        'error_handler' => 'DefaultLogger',

        // Loggers
        'loggers' => [

            'DefaultLogger' => [
                'handlers' => [
                    [
                        'class' => Monolog\Handler\NullHandler::class,
                        'params' => [
                            'level' => Monolog\Logger::DEBUG
                        ]
                    ]
                ]
            ]
        ]
    ]
];
```
