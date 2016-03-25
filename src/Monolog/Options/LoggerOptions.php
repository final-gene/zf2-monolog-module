<?php
/**
 * Logger options file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Logger options
 *
 * @package FinalGene\MonologModule\Monolog\Options
 */
class LoggerOptions extends AbstractOptions
{
    /**
     * @var \FinalGene\MonologModule\Monolog\Options\HandlerOptions[]
     */
    protected $handlers;

    /**
     * @var \FinalGene\MonologModule\Monolog\Options\ObjectOptions[]
     */
    protected $processors;

    /**
     * Get handlers
     *
     * @return \FinalGene\MonologModule\Monolog\Options\HandlerOptions[]
     */
    public function getHandlers()
    {
        if (null == $this->handlers) {
            return [];
        }
        return $this->handlers;
    }

    /**
     * Set handlers
     *
     * @param array|\Traversable|\FinalGene\MonologModule\Monolog\Options\HandlerOptions[] $handlers
     *
     * @return \FinalGene\MonologModule\Monolog\Options\LoggerOptions
     */
    public function setHandlers($handlers)
    {
        $this->handlers = array_map(function ($handler) {
            if ($handler instanceof HandlerOptions) {
                return $handler;
            }

            $options = new HandlerOptions();
            $options->setFromArray($handler);
            return $options;
        }, $handlers);
        return $this;
    }

    /**
     * Get processors
     *
     * @return \FinalGene\MonologModule\Monolog\Options\ObjectOptions[]
     */
    public function getProcessors()
    {
        if (null === $this->processors) {
            return [];
        }
        return $this->processors;
    }

    /**
     * Set processors
     *
     * @param array|\Traversable|\FinalGene\MonologModule\Monolog\Options\ObjectOptions[] $processors
     *
     * @return \FinalGene\MonologModule\Monolog\Options\HandlerOptions
     */
    public function setProcessors($processors)
    {
        $this->processors = array_map(function ($processor) {
            if ($processor instanceof ObjectOptions) {
                return $processor;
            }
            $options = new ObjectOptions();
            $options->setFromArray($processor);
            return $options;
        }, $processors);
        return $this;
    }
}
