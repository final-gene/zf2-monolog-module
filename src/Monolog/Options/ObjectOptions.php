<?php
/**
 * Object options file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Exception\BadMethodCallException;

/**
 * Object options
 *
 * @package FinalGene\MonologModule\Monolog\Options
 */
class ObjectOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $params;

    /**
     * Get class
     *
     * @throws \Zend\Stdlib\Exception\BadMethodCallException
     *
     * @return string
     */
    public function getClass()
    {
        if (null === $this->class) {
            throw new BadMethodCallException('Class not yet set');
        }
        return $this->class;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return \FinalGene\MonologModule\Monolog\Options\ObjectOptions
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        if (null === $this->params) {
            return [];
        }
        return $this->params;
    }

    /**
     * Set params
     *
     * @param array $params
     *
     * @return \FinalGene\MonologModule\Monolog\Options\ObjectOptions
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}
