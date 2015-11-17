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
use Iresults\DateTime\TimeSlot\TimeSlot;
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
class TimeSlotTest extends BaseTestCase
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
     * @test
     * @expectedException \UnexpectedValueException
     * @expectedExceptionCode 1442234594
     */
    public function initWithStartTimeAfterEndTimeTest()
    {
        new TimeSlot(new DateTime('Thu 15.09.2015'), new DateTime('Mon 14.09.2015'));
    }

    /**
     * @test
     */
    public function getDurationTest()
    {
        $date1 = new DateTime();
        $date2 = new DateTime();

        $this->assertInstanceOf(DateInterval::class, (new TimeSlot($date1, $date2))->getDuration());
        $this->assertSame(0, (new TimeSlot($date1, $date2))->getDuration()->s);
        $this->assertSame(0, (new TimeSlot($date1, $date2))->getDuration()->i);

        $date1 = new DateTime('2015-09-10 14:30:00');
        $date2 = new DateTime('2015-09-10 14:31:00');
        $this->assertInstanceOf(DateInterval::class, (new TimeSlot($date1, $date2))->getDuration());
        $this->assertSame(0, (new TimeSlot($date1, $date2))->getDuration()->s);
        $this->assertSame(1, (new TimeSlot($date1, $date2))->getDuration()->i);
    }

    /**
     * @test
     */
    public function getDayOfWeekTest()
    {
        $date1 = new DateTime('Mon 14.09.2015');
        $date2 = new DateTime('Mon 14.09.2015');
        $this->assertSame('Mon', (new TimeSlot($date1, $date2))->getDayOfWeek());

        $date1 = new DateTime('2015-09-10 14:30:00');
        $date2 = new DateTime('2015-09-10 14:31:00');
        $this->assertSame('Thu', (new TimeSlot($date1, $date2))->getDayOfWeek());
    }

    /**
     * @test
     */
    public function timeSlotFromStringTest()
    {
        $timeSlot = TimeSlot::createFromString('2015-09-14 13:20:00#2015-12-23 21:45:00');
        $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $timeSlot->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $timeSlot->getEnd()->format('Y-m-d H:i:s P'));

        $timeSlot = TimeSlot::createFromString('1442229600#1450903500');
        $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $timeSlot->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $timeSlot->getEnd()->format('Y-m-d H:i:s P'));

        $timeSlot = TimeSlot::createFromString('@1442229600#@1450903500');
        $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        $this->assertEquals('2015-09-14 13:20:00 +02:00', $timeSlot->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $timeSlot->getEnd()->format('Y-m-d H:i:s P'));

        $timeSlot = TimeSlot::createFromString('2015-12-23T21:45:00+01:00#2016-05-12T13:04:18+02:00');
        $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $timeSlot->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2016-05-12 13:04:18 +02:00', $timeSlot->getEnd()->format('Y-m-d H:i:s P'));

        $timeSlot = TimeSlot::createFromString('@1450903500#@1463051058');
        $this->assertInstanceOf(TimeSlot::class, $timeSlot);
        $this->assertEquals('2015-12-23 21:45:00 +01:00', $timeSlot->getStart()->format('Y-m-d H:i:s P'));
        $this->assertEquals('2016-05-12 13:04:18 +02:00', $timeSlot->getEnd()->format('Y-m-d H:i:s P'));
    }

    /**
     * @test
     */
    public function serializeAndUnserializeTest()
    {
        date_default_timezone_set('Europe/Vaduz');
        $start = $end = new DateTime();
        $timeSlot = new TimeSlot($start, $end);
        $serializedTimeSlot = $timeSlot->toString();

        $this->assertEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));

        date_default_timezone_set('GMT');

        $this->assertEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));

        $timeSlot = TimeSlot::createFromString($serializedTimeSlot);
        $this->assertEquals($start->getTimestamp(), $timeSlot->getStart()->getTimestamp());
        $this->assertEquals($end->getTimestamp(), $timeSlot->getEnd()->getTimestamp());

        $this->assertNotEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertNotEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));


        date_default_timezone_set('Europe/Vaduz');
        $start = $end = new DateTime('2015-12-23T14:30:00');
        $timeSlot = new TimeSlot($start, $end);
        $serializedTimeSlot = $timeSlot->toString();

        $this->assertEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));

        date_default_timezone_set('GMT');

        $this->assertEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));

        $timeSlot = TimeSlot::createFromString($serializedTimeSlot);
        $this->assertEquals($start->getTimestamp(), $timeSlot->getStart()->getTimestamp());
        $this->assertEquals($end->getTimestamp(), $timeSlot->getEnd()->getTimestamp());

        $this->assertNotEquals($start->format('Y-m-d H:i:s P e'), $timeSlot->getStart()->format('Y-m-d H:i:s P e'));
        $this->assertNotEquals($end->format('Y-m-d H:i:s P e'), $timeSlot->getEnd()->format('Y-m-d H:i:s P e'));
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
            (new TimeSlot($date1, $date2))->toString()
        );

        $this->assertEquals(
            '2015-09-14T13:20:00+02:00#2015-12-23T21:45:00+01:00',
            (string)(new TimeSlot($date1, $date2))
        );
    }

    /**
     * @test
     */
    public function overlapsTimeSlotTest()
    {
        $this->assertTrue(
            TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00'))
        );
        $this->assertTrue(
            TimeSlot::createFromString('2016-05-12T17:30:00+02:00#2016-05-12T18:00:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00'))
        );
        $this->assertTrue(
            TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T17:01:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00'))
        );
        $this->assertTrue(
            TimeSlot::createFromString('2016-05-12T17:30:00+02:00#2016-05-12T18:30:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T18:00:00+02:00#2016-05-12T19:00:00+02:00'))
        );
        $this->assertFalse(
            TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T18:30:00+02:00#2016-05-12T19:00:00+02:00'))
        );
        $this->assertFalse(
            TimeSlot::createFromString('2016-05-12T17:00:00+02:00#2016-05-12T18:00:00+02:00')
                ->overlapsTimeSlot(TimeSlot::createFromString('2016-05-12T18:00:00+02:00#2016-05-12T19:00:00+02:00'))
        );
    }
}
