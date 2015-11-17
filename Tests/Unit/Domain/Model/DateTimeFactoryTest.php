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
 * Created 29.09.15 17:36
 */


namespace Iresults\ResourceBooking\Tests\Unit\Domain\Model;


use DateTimeInterface;
use Iresults\DateTime\DateTimeFactory;
use Iresults\DateTime\Time;
use Iresults\ResourceBooking\Tests\BaseTestCase;

class DateTimeFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function createTest()
    {
        $result = DateTimeFactory::create(new \DateTime('2015-09-29T17:37:21'));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));

        $result = DateTimeFactory::create('2015-09-29T17:37:21');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));

        $result = DateTimeFactory::create('1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));

        $result = DateTimeFactory::create(1443541041);
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));

        $result = DateTimeFactory::create('@1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));

        $result = DateTimeFactory::create('29.09.2015 17:37:21');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));
    }

    /**
     * @test
     */
    public function createWithTimeZoneSwitchTest()
    {
        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create(new \DateTime('2015-09-29T17:37:21'));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+00:00', $result->format('c'));

        $result = DateTimeFactory::create('2015-09-29T17:37:21');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+00:00', $result->format('c'));


        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create('1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T15:37:21+00:00', $result->format('c'));

        date_default_timezone_set('Europe/Vaduz');
        $result = DateTimeFactory::create('1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));


        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create(1443541041);
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T15:37:21+00:00', $result->format('c'));

        date_default_timezone_set('Europe/Vaduz');
        $result = DateTimeFactory::create(1443541041);
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));


        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create('@1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T15:37:21+00:00', $result->format('c'));

        date_default_timezone_set('Europe/Vaduz');
        $result = DateTimeFactory::create('@1443541041');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T17:37:21+02:00', $result->format('c'));


        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create('2015-10-02T11:44:02+02:00');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-10-02T09:44:02+00:00', $result->format('c'));

        date_default_timezone_set('Europe/Vaduz');
        $result = DateTimeFactory::create('2015-10-02T11:44:02+02:00');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-10-02T11:44:02+02:00', $result->format('c'));


        date_default_timezone_set('GMT');
        $result = DateTimeFactory::create('2015-12-13T11:44:02+02:00');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-12-13T09:44:02+00:00', $result->format('c'));

        date_default_timezone_set('Europe/Vaduz');
        $result = DateTimeFactory::create('2015-12-13T11:44:02+02:00');
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-12-13T10:44:02+01:00', $result->format('c'));


    }

    /**
     * @test
     */
    public function currentTest()
    {
        $this->assertEquals(new \DateTime(), DateTimeFactory::current());
    }

    /**
     * @test
     */
    public function simulateCurrentTimeTest()
    {
        DateTimeFactory::simulateCurrentTime(new \DateTime('1986-11-13T22:30:00+01:00'));
        $this->assertEquals(new \DateTime('1986-11-13T22:30:00+01:00'), DateTimeFactory::current());
    }

    /**
     * @test
     */
    public function currentByKeyWordTest()
    {
        DateTimeFactory::simulateCurrentTime(new \DateTime('1986-11-13T22:30:00+01:00'));
        $this->assertEquals(new \DateTime('1986-11-13T22:30:00+01:00'), DateTimeFactory::create('current'));
    }


    /**
     * @test
     */
    public function createWithTimeTest()
    {
        $result = DateTimeFactory::createWithTime(new \DateTime('2015-09-29T17:37:21'), new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));

        $result = DateTimeFactory::createWithTime('2015-09-29T17:37:21', new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));

        $result = DateTimeFactory::createWithTime('1443541041', new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));

        $result = DateTimeFactory::createWithTime(1443541041, new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));

        $result = DateTimeFactory::createWithTime('@1443541041', new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));

        $result = DateTimeFactory::createWithTime('29.09.2015 17:37:21', new Time(14, 23, 58));
        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2015-09-29T14:23:58+02:00', $result->format('c'));
    }
}
