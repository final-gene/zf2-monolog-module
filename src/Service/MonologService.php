<?php
/**
 * Monolog service file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Service;

/**
 * Monolog service
 *
 * @package FinalGene\MonologModule\Service
 */
class MonologService
{
    /**
     * @var array
     */
    protected $config;

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
            return [];
        }

        return $this->config;
    }

    /**
     * Set config
     *
     * @param array $config
     *
     * @return \FinalGene\MonologModule\Service\MonologService
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
}
