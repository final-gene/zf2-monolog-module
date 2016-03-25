<?php
/**
 * Service manager config file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

use FinalGene\MonologModule;

return [
    'service_manager' => [
        'initializers' => [
        ],
        'invokables' => [
        ],
        'factories' => [
            MonologModule\Monolog\LoggerManager::class =>  MonologModule\Monolog\LoggerManagerFactory::class,
            MonologModule\Service\MonologService::class => MonologModule\Service\MonologServiceFactory::class,
        ],
    ],
];
