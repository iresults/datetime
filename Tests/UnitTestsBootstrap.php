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
 * Created 16.06.15 11:22
 */


namespace Iresults\ResourceBooking\Tests;

/**
 * Bootstrapping for unit tests
 *
 * @package Iresults\ResourceBooking\Tests
 */
class UnitTestsBootstrap
{
    /**
     * Bootstrap the testing environment
     */
    public function bootstrapSystem()
    {
        $this->registerAutoloader();
        $this->buildFixtureClasses();
        $this->registerBaseTestCase();
    }

    /**
     * Require composer's autoloader
     */
    protected function registerAutoloader()
    {
        require_once __DIR__.'/../vendor/autoload.php';
    }

    /**
     * Build the classes that will be used in the unit tests
     */
    protected function buildFixtureClasses()
    {
        //require_once __DIR__.'/FixtureClasses/ObjectStorage.php';
        //class_alias(
        //    'Iresults\ResourceBooking\Tests\FixtureClasses\ObjectStorage',
        //    'TYPO3\CMS\Extbase\Persistence\ObjectStorage'
        //);
        //require_once __DIR__.'/FixtureClasses/Repository.php';
        //class_alias(
        //    'Iresults\ResourceBooking\Tests\FixtureClasses\Repository',
        //    'TYPO3\CMS\Extbase\Persistence\Repository'
        //);
    }

    /**
     * Load the extended PHPUnit test case
     */
    protected function registerBaseTestCase()
    {
        require_once __DIR__.'/BaseTestCase.php';
    }
}

$bootstrap = new UnitTestsBootstrap();
$bootstrap->bootstrapSystem();
unset($bootstrap);