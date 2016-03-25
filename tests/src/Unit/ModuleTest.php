<?php
/**
 * This file is part of the monolog-module.php project.
 *
 * @copyright Copyright (c) 2016, final gene <info@final-gene.de>
 * @author    Frank Giesecke <frank.giesecke@final-gene.de>
 */

namespace FinalGene\MonologModuleTest\Unit;

use FinalGene\MonologModule\Module;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class ModuleTest
 *
 * @package FinalGene\MonologModuleTest\Unit
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Make sure module config can be serialized.
     *
     * Make sure module config can be serialized, because if not,
     * this breaks the application when zf2's config cache is enabled.
     *
     * @covers \FinalGene\MonologModule\Module::getConfig()
     * @uses \FinalGene\MonologModule\Module::loadConfig()
     */
    public function testModuleConfigIsSerializable()
    {
        $module = new Module();

        if (!$module instanceof ConfigProviderInterface) {
            $this->markTestSkipped('Module does not provide config');
        }

        $this->assertEquals($module->getConfig(), unserialize(serialize($module->getConfig())));
    }
}
