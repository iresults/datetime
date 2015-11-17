<?php
/*
 *  Copyright notice
 *
 *  (c) 2015 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *  Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * @author COD
 * Created 16.06.15 12:04
 */

namespace Iresults\ResourceBooking\Tests;


/**
 * Base test case
 *
 * @package Iresults\ResourceBooking\Tests
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $originalDefaultTimezone = '';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->originalDefaultTimezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Vaduz');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        date_default_timezone_set($this->originalDefaultTimezone);
        parent::tearDown();
    }

    /**
     * Injects $dependency into property $name of $target
     *
     * This is a convenience method for setting a protected or private property in
     * a test subject for the purpose of injecting a dependency.
     *
     * @param object $target     The instance which needs the dependency
     * @param string $name       Name of the property to be injected
     * @param mixed  $dependency The dependency to inject – usually an object but can also be any other type
     * @return void
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    protected function inject($target, $name, $dependency)
    {
        if (!is_object($target)) {
            throw new \InvalidArgumentException('Wrong type for argument $target, must be object.');
        }

        $objectReflection = new \ReflectionObject($target);
        $methodNamePart = strtoupper($name[0]).substr($name, 1);
        if ($objectReflection->hasMethod('set'.$methodNamePart)
            && is_callable(array($target, 'set'.$methodNamePart))
        ) {
            $methodName = 'set'.$methodNamePart;
            $target->$methodName($dependency);
        } elseif ($objectReflection->hasMethod('inject'.$methodNamePart)
            && is_callable(array($target, 'inject'.$methodNamePart))
        ) {
            $methodName = 'inject'.$methodNamePart;
            $target->$methodName($dependency);
        } elseif ($objectReflection->hasProperty($name)) {
            $property = $objectReflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($target, $dependency);
        } else {
            throw new \RuntimeException('Could not inject '.$name.' into object of type '.get_class($target));
        }
    }


}
