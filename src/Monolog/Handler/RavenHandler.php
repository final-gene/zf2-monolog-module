<?php
/**
 * Raven handler file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModule\Monolog\Handler;

use Raven_Client;
use Monolog\Logger;

/**
 * Raven handler
 *
 * @package FinalGene\MonologModule\Monolog\Handler
 */
class RavenHandler extends \Monolog\Handler\RavenHandler
{
    /**
     * Constructor
     *
     * @param string $dsn
     * @param bool|int $level
     * @param bool $bubble
     * @param array $options
     */
    public function __construct($dsn = null, $level = Logger::DEBUG, $bubble = true, $options = [])
    {
        $client = new Raven_Client($dsn, $options);

        if (isset($options['user_context'])) {
            $client->user_context($options['user_context']);
        }

        if (isset($options['tags_context'])) {
            $client->user_context($options['tags_context']);
        }

        if (isset($options['extra_context'])) {
            $client->user_context($options['extra_context']);
        }

        parent::__construct($client, $level, $bubble);
    }
}
