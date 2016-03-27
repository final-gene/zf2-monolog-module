<?php
/**
 * Module file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule;

use FinalGene\MonologModule\Monolog\LoggerManager;
use FinalGene\MonologModule\Service\MonologService;
use Monolog\ErrorHandler;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Mvc\Application;

/**
 * Module
 *
 * @package FinalGene\MonologModule
 */
class Module implements BootstrapListenerInterface, ConfigProviderInterface, DependencyIndicatorInterface
{
    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $event
     *
     * @return array
     */
    public function onBootstrap(EventInterface $event)
    {
        $application = $event->getTarget();
        if (!$application instanceof Application) {
            return;
        }

        $serviceManager = $application->getServiceManager();
        if ($serviceManager->has(MonologService::class) &&
            $serviceManager->has(LoggerManager::class)
        ) {
            /** @var $monologService MonologService */
            $monologService = $serviceManager->get(MonologService::class);

            /** @var $monologLoggerManager LoggerManager */
            $monologLoggerManager = $serviceManager->get(LoggerManager::class);

            $this->registerMonologErrorLogger($monologService, $monologLoggerManager);
        }
    }

    /**
     * Register monolog error logger
     *
     * @param MonologService $service
     * @param LoggerManager  $loggerManager
     */
    public function registerMonologErrorLogger(MonologService $service, LoggerManager $loggerManager)
    {
        $monologConfig = $service->getConfig();
        if (!isset($monologConfig['error_logger'])) {
            return;
        }

        $errorLogger = $monologConfig['error_logger'];
        if (!$loggerManager->has($errorLogger)) {
            return;
        }

        ErrorHandler::register($loggerManager->get($errorLogger));
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        $config = [];
        $configFiles = [
            'config/service.config.php',
        ];

        foreach ($configFiles as $configFile) {
            $config = array_merge_recursive($config, $this->loadConfig($configFile));
        }

        return $config;
    }

    /**
     * Load config
     *
     * @param string $name Name of the configuration
     *
     * @throws \InvalidArgumentException if config could not be loaded
     *
     * @return array
     */
    protected function loadConfig($name)
    {
        $filename = __DIR__ . '/../' . $name;
        if (!is_readable($filename)) {
            throw new \InvalidArgumentException('Could not load config ' . $name);
        }

        /** @noinspection PhpIncludeInspection */
        return require $filename;
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
        ];
    }
}
