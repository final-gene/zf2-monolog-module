<?php
/**
 * Handler options file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog\Options;

/**
 * Handler options
 *
 * @package FinalGene\MonologModule\Monolog\Options
 */
class HandlerOptions extends ObjectOptions
{
    /**
     * @var \FinalGene\MonologModule\Monolog\Options\ObjectOptions
     */
    protected $formatter;

    /**
     * @var \FinalGene\MonologModule\Monolog\Options\ObjectOptions[]
     */
    protected $processors;

    /**
     * Get formatter
     *
     * @return null|\FinalGene\MonologModule\Monolog\Options\ObjectOptions
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Set formatter
     *
     * @param array|\Traversable|\FinalGene\MonologModule\Monolog\Options\ObjectOptions $formatter
     *
     * @return \FinalGene\MonologModule\Monolog\Options\HandlerOptions
     */
    public function setFormatter($formatter)
    {
        if ($formatter instanceof ObjectOptions) {
            $this->formatter = $formatter;
        } else {
            $this->formatter = new ObjectOptions();
            $this->formatter->setFromArray($formatter);
        }
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
