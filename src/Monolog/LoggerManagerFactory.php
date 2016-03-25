<?php
/**
 * Logger manager factory file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog;

use FinalGene\MonologModule\Service\MonologService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Logger manager factory
 *
 * @package FinalGene\MonologModule\Monolog
 */
class LoggerManagerFactory implements FactoryInterface
{
    /**
     * Create logger manager
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \FinalGene\MonologModule\Monolog\LoggerManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $loggerManager = new LoggerManager();

        /** @var $monologService \FinalGene\MonologModule\Service\MonologService */
        $monologService = $serviceLocator->get(MonologService::class);

        $monologConfig = $monologService->getConfig();
        if (isset($monologConfig['loggers']) && is_array($monologConfig['loggers'])) {
            $loggerManager->setConfig($monologConfig['loggers']);
        }

        return $loggerManager;
    }
}
