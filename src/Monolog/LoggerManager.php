<?php
/**
 * Logger manager file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog;

use FinalGene\MonologModule\Monolog\Options\LoggerOptions;
use FinalGene\MonologModule\Monolog\Options\ObjectOptions;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Zend\Code\Reflection\ClassReflection;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Exception\RuntimeException;

/**
 * Logger manager
 *
 * @package FinalGene\MonologModule\Monolog
 */
class LoggerManager implements ServiceLocatorInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \FinalGene\MonologModule\Monolog\Options\LoggerOptions[]
     */
    protected $options;

    /**
     * @var \Psr\Log\LoggerInterface[]
     */
    protected $instances;

    /**
     * Get config
     *
     * @throws \Zend\Stdlib\Exception\BadMethodCallException
     *
     * @return array
     */
    public function getConfig()
    {
        if (null === $this->config) {
            return array();
        }

        return $this->config;
    }

    /**
     * Set config
     *
     * @param array $config
     *
     * @return \FinalGene\MonologModule\Monolog\LoggerManager
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get options
     *
     * @param string $name
     *
     * @return null|\FinalGene\MonologModule\Monolog\Options\LoggerOptions
     */
    protected function getOptions($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        $config = $this->getConfig();
        if (isset($config[$name])) {
            $options = new LoggerOptions();
            $options->setFromArray($config[$name]);
            $this->options[$name] = $options;
            return $this->options[$name];
        }

        return null;
    }

    /**
     * Resolve constants
     *
     * @param array $array
     *
     * @return array
     */
    protected function resolveConstants($array = [])
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->resolveConstants($value);
            } elseif (is_string($value) && defined($value)) {
                $value = constant($value);
            }
        }

        return $array;
    }

    /**
     * Create object
     *
     * @param \FinalGene\MonologModule\Monolog\Options\ObjectOptions $options
     *
     * @return object
     */
    protected function createObject(ObjectOptions $options)
    {
        $class = $options->getClass();

        if (null === $class) {
            throw new RuntimeException('Cannot create logger object');
        }

        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Cannot create logger object %s', $class));
        }

        $params = $options->getParams();
        if (empty($params)) {
            return new $class();
        }

        return call_user_func_array(
            [new ClassReflection($class), 'newInstance'],
            $this->resolveConstants($params)
        );
    }

    /**
     * Create handler
     *
     * @param \FinalGene\MonologModule\Monolog\Options\HandlerOptions $options
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createHandler($options)
    {
        try {
            $handler = $this->createObject($options);
        } catch (RuntimeException $exception) {
            throw new RuntimeException('Cannot create logger handler', 0, $exception);
        }

        if (!$handler instanceof HandlerInterface) {
            throw new RuntimeException(sprintf(
                'Logger handler %s must implement \Monolog\Handler\HandlerInterface',
                $options->getClass()
            ));
        }

        $formatter = $options->getFormatter();
        if (null !== $formatter) {
            $handler->setFormatter($this->createFormatter($formatter));
        }

        foreach ($options->getProcessors() as $processor) {
            $handler->pushProcessor($this->createProcessor($processor));
        }

        return $handler;
    }

    /**
     * Create formatter
     *
     * @param \FinalGene\MonologModule\Monolog\Options\ObjectOptions $options
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function createFormatter($options)
    {
        try {
            $formatter = $this->createObject($options);
        } catch (RuntimeException $exception) {
            throw new RuntimeException('Cannot create logger formatter', 0, $exception);
        }

        if (!$formatter instanceof FormatterInterface) {
            throw new RuntimeException(sprintf(
                'Logger formatter %s must implement \Monolog\Formatter\FormatterInterface',
                $options->getClass()
            ));
        }

        return $formatter;
    }

    /**
     * Create processor
     *
     * @param \FinalGene\MonologModule\Monolog\Options\ObjectOptions $options
     *
     * @return callable
     */
    protected function createProcessor($options)
    {
        try {
            $processor = $this->createObject($options);
        } catch (RuntimeException $exception) {
            throw new RuntimeException('Cannot create logger processor', 0, $exception);
        }

        /** @var $processor callable */
        if (!is_callable($processor)) {
            throw new RuntimeException(sprintf(
                'Logger processor %s must be callable',
                $options->getClass()
            ));
        }

        return $processor;
    }

    /**
     * Retrieve a registered instance
     *
     * @param  string $name
     *
     * @throws Exception\ServiceNotFoundException
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function get($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $options = $this->getOptions($name);
        if ($options instanceof LoggerOptions) {
            $logger = new Logger($name);
            foreach ($options->getHandlers() as $handler) {
                $logger->pushHandler($this->createHandler($handler));
            }
            foreach ($options->getProcessors() as $processor) {
                $logger->pushProcessor($this->createProcessor($processor));
            }
            $this->instances[$name] = $logger;
            return $this->instances[$name];
        }

        throw new Exception\ServiceNotFoundException(sprintf(
            '%s was unable to fetch necessary options for %s',
            get_class($this) . '::' . __FUNCTION__,
            $name
        ));
    }

    /**
     * Check for a registered instance
     *
     * @param  string $name
     *
     * @return bool
     */
    public function has($name)
    {
        $config = $this->getConfig();
        return isset($config[$name]);
    }
}
