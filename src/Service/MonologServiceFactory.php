<?php
/**
 * Monolog service factory file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Monolog service factory
 *
 * @package FinalGene\MonologModule\Service
 */
class MonologServiceFactory implements FactoryInterface
{
    /**
     * Create logger manager
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \FinalGene\MonologModule\Service\MonologService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $monologService = new MonologService();

        $config = $serviceLocator->get('config');
        if (isset($config['monolog']) && is_array($config['monolog'])) {
            $monologService->setConfig($config['monolog']);
        }

        return $monologService;
    }
}
