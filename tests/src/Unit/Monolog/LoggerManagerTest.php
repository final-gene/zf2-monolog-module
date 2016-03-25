<?php
/**
 * Logger manager test file
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModuleTest\Unit\Monolog;

use FinalGene\MonologModule\Monolog\LoggerManager;

/**
 * Logger manager test
 *
 * @package FinalGene\MonologModuleTest\Unit\Monolog
 */
class LoggerManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testLoggerManagerWithNoHandlers()
    {
        $loggerManager = new LoggerManager();
        $loggerManager->setConfig(array(
            'TestLogger' => array()
        ));

        $this->assertTrue($loggerManager->has('TestLogger'));
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $loggerManager->get('TestLogger'));
    }

    public function testLoggerManagerWithOneNullHandler()
    {
        $loggerManager = new LoggerManager();
        $loggerManager->setConfig(array(
            'TestLogger' => array(
                'handlers' => array(
                    array(
                        'class' => 'Monolog\Handler\NullHandler',
                        'params' => array(
                            'level' => 'Monolog\Logger::DEBUG'
                        ),
                        'formatter' => array(
                            'class' => 'Monolog\Formatter\NormalizerFormatter',
                            'params' => array(
                                'dateFormat' => 'Y-m-d H:i:s'
                            )
                        )
                    )
                )
            )
        ));

        /** @var $logger \Monolog\Logger */
        $logger = $loggerManager->get('TestLogger');

        $handlers = $logger->getHandlers();
        $this->assertCount(1, $handlers);
        $this->assertContainsOnlyInstancesOf('Monolog\Handler\NullHandler', $handlers);

        $formatter = $handlers[0]->getFormatter();
        $this->assertInstanceOf('Monolog\Formatter\NormalizerFormatter', $formatter);
    }
}
