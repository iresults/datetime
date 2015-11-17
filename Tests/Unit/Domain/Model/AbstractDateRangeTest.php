<?php

namespace Iresults\ResourceBooking\Tests\Unit\Domain\Model\TimeSlot;

/*
 *  Copyright notice
 *
 *  (c) 2015 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *           Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use DateInterval;
use DateTime;
use Iresults\DateTime\AbstractDateRange;
use Iresults\DateTime\DateRangeInterface;
use Iresults\ResourceBooking\Tests\BaseTestCase;

/**
 * Test case for class \Iresults\DateTime\TimeSlot\TimeSlot.
 *
 * @copyright Copyright belongs to the respective authors
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author    Andreas Thurnheer-Meier <tma@iresults.li>
 * @author    Daniel Corn <cod@iresults.li>
 */
class AbstractDateRangeTest extends BaseTestCase
{
    /**
     * @return AbstractDateRange
     */
    protected function newFixture()
    {
        $constructorArguments = func_get_args();

        return $this
            ->getMockBuilder(AbstractDateRange::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs($constructorArguments)
            ->setMethods(array('no'))
            ->getMock();
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     * @expectedExceptionCode 1442234594
     */
    public function initWithStartTimeAfterEndTimeTest()
    {
        $this->newFixture(new DateTime('Thu 15.09.2015'), new DateTime('Mon 14.09.2015'));
    }

    /**
     * @test
     */
    public function getDurationTest()
    {
        $date1 = new DateTime();
        $date2 = new DateTime();

        $this->assertInstanceOf(DateInterval::class, $this->newFixture($date1, $date2)->getDuration());
        $this->assertSame(0, $this->newFixture($date1, $date2)->getDuration()->s);
        $this->assertSame(0, $this->newFixture($date1, $date2)->getDuration()->i);

        $date1 = new DateTime('2015-09-10 14:30:00');
        $date2 = new DateTime('2015-09-10 14:31:00');
        $this->assertInstanceOf(DateInterval::class, $this->newFixture($date1, $date2)->getDuration());
        $this->assertSame(0, $this->newFixture($date1, $date2)->getDuration()->s);
        $this->assertSame(1, $this->newFixture($date1, $date2)->getDuration()->i);
    }

    /**
     * @test
     */
    public function getDurationInSecondsTest()
    {
        $date1 = new DateTime();
        $date2 = new DateTime();

        $this->assertInternalType('int', $this->newFixture($date1, $date2)->getDuration(true));
        $this->assertSame(0, $this->newFixture($date1, $date2)->getDuration(true));

        $date1 = new DateTime('2015-09-10 14:30:00');
        $date2 = new DateTime('2015-09-10 14:31:00');
        $this->assertInternalType('int', $this->newFixture($date1, $date2)->getDuration(true));
        $this->assertSame(60, $this->newFixture($date1, $date2)->getDuration(true));

        $date1 = new DateTime('2015-09-10 14:30:00');
        $date2 = new DateTime('2015-09-11 14:30:00');
        $this->assertInternalType('int', $this->newFixture($date1, $date2)->getDuration(true));
        $this->assertSame(24 * 60 * 60, $this->newFixture($date1, $date2)->getDuration(true));
    }

    /**
     * @test
     */
    public function createFromStringTest()
    {
        $dateRange = AbstractDateRange::createFromString('2015-09-14 13:20:00#2015-12-23 21:45:00');
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $dateRange->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $dateRange->getEnd()->format('Y-m-d H:i:s P'));

        $dateRange = AbstractDateRange::createFromString('1442229600#1450903500');
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $dateRange->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $dateRange->getEnd()->format('Y-m-d H:i:s P'));

        $dateRange = AbstractDateRange::createFromString('@1442229600#@1450903500');
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $dateRange->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $dateRange->getEnd()->format('Y-m-d H:i:s P'));

        $dateRange = AbstractDateRange::createFromString('2015-12-23T21:45:00+01:00#2016-05-12T13:04:18+02:00');
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $dateRange->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2016-05-12 13:04:18 +02:00', $dateRange->getEnd()->format('Y-m-d H:i:s P'));

        $dateRange = AbstractDateRange::createFromString('@1450903500#@1463051058');
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $dateRange->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2016-05-12 13:04:18 +02:00', $dateRange->getEnd()->format('Y-m-d H:i:s P'));
    }


    /**
     * @test
     */
    public function createWithSpanTest()
    {
        $start = new DateTime('now');

        $dateRange = AbstractDateRange::createWithSpan($start, DateRangeInterface::UNIT_HALF_HOUR);
        $this->assertSame('30', $dateRange->getDuration()->format('%I'));

        $dateRange = AbstractDateRange::createWithSpan($start, DateRangeInterface::UNIT_HOUR);
        $this->assertSame('01', $dateRange->getDuration()->format('%H'));

        $dateRange = AbstractDateRange::createWithSpan($start, DateRangeInterface::UNIT_DAY);
        $this->assertSame('01', $dateRange->getDuration()->format('%D'));

        $dateRange = AbstractDateRange::createWithSpan($start, '+ 2 minutes');
        $this->assertSame('02', $dateRange->getDuration()->format('%I'));

        $dateRange = AbstractDateRange::createWithSpan($start, '+ 62 minutes');
        $this->assertSame('02', $dateRange->getDuration()->format('%I'));

        $dateRange = AbstractDateRange::createWithSpan($start, '+ 2 days');
        $this->assertSame('02', $dateRange->getDuration()->format('%D'));
    }

    /**
     * @test
     */
    public function createEmptyTest()
    {
        $dateRange = AbstractDateRange::createEmpty(new DateTime('now'));
        $this->assertSame(0, $dateRange->getDuration(true));
        $this->assertTrue($dateRange->isEmpty());

        $dateRange = AbstractDateRange::createEmpty(new DateTime('2016-05-12T13:04:18+02:00'));
        $this->assertSame(0, $dateRange->getDuration(true));
        $this->assertTrue($dateRange->isEmpty());

        $dateRange = AbstractDateRange::createEmpty();
        $this->assertSame(0, $dateRange->getDuration(true));
        $this->assertTrue($dateRange->isEmpty());
    }

    /**
     * @test
     */
    public function getIteratorForUnitForEmptyTest()
    {
        $dateRange = AbstractDateRange::createEmpty(new DateTime('now'));
        $this->assertSame(0, $dateRange->getIteratorForUnit(DateRangeInterface::UNIT_DAY)->count());
        $this->assertSame(0, $dateRange->getIteratorForUnit(DateRangeInterface::UNIT_HALF_HOUR)->count());
        $this->assertSame(0, $dateRange->getIteratorForUnit(DateRangeInterface::UNIT_HOUR)->count());
    }

    /**
     * @test
     */
    public function toStringTest()
    {
        $date1 = new DateTime('2015-09-14 13:20:00');
        $date2 = new DateTime('2015-12-23 21:45:00');
        $this->assertEquals(
            '2015-09-14T13:20:00+02:00#2015-12-23T21:45:00+01:00',
            $this->newFixture($date1, $date2)->toString()
        );

        $this->assertEquals(
            '2015-09-14T13:20:00+02:00#2015-12-23T21:45:00+01:00',
            (string)$this->newFixture($date1, $date2)
        );
    }

    /**
     * @test
     */
    public function convertBackAndForceTest()
    {
        $string = '2015-12-23T21:45:00+01:00#2016-05-12T13:04:18+02:00';

        /** @var AbstractDateRange $dateRange */
        $dateRange = AbstractDateRange::createFromString($string);
        $this->assertInstanceOf(AbstractDateRange::class, $dateRange);
        $this->assertSame($string, (string)$dateRange);

        $dateRange = AbstractDateRange::createFromString((string)$dateRange);
        $this->assertInstanceOf(AbstractDateRange::class, $dateRange);
        $this->assertSame($string, (string)$dateRange);
    }
}
