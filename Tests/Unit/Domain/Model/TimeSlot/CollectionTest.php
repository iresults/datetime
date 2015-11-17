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
 * Created 14.09.15 12:47
 */


namespace Iresults\ResourceBooking\Tests\Unit\Domain\Model\TimeSlot;


use Iresults\DateTime\TimeSlot\Collection;
use Iresults\DateTime\TimeSlot\TimeSlot;

/**
 * Collection class for TimeSlots
 *
 * @package Iresults\ResourceBooking\Tests\Unit\Domain\Model\TimeSlot
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new Collection(
            new TimeSlot(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            )
        );
    }

    /**
     * @test
     */
    public function createWithoutTimeSlotTest()
    {
        $collection = new Collection();
        $this->assertEmpty($collection);
        $this->assertSame(0, $collection->count());
        $this->assertNull($collection->current());
        $this->assertFalse($collection->valid());
    }

    /**
     * @test
     */
    public function createWithVariableArgumentsTest()
    {
        $collection = new Collection(
            new TimeSlot(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            ),
            new TimeSlot(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            ),
            new TimeSlot(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            )
        );
        $this->assertSame(3, $collection->count());
    }

    /**
     * @test
     */
    public function createWithArrayArgumentsTest()
    {
        $collection = new Collection(
            [
                new TimeSlot(
                    new \DateTime('Mon 14.09.2015 12:00'),
                    new \DateTime('Mon 14.09.2015 13:00')
                ),
                new TimeSlot(
                    new \DateTime('Mon 14.09.2015 12:00'),
                    new \DateTime('Mon 14.09.2015 13:00')
                ),
                new TimeSlot(
                    new \DateTime('Mon 14.09.2015 12:00'),
                    new \DateTime('Mon 14.09.2015 13:00')
                )
            ]
        );
        $this->assertSame(3, $collection->count());
    }

    /**
     * @test
     * @expectedException \Iresults\DateTime\Exception\InvalidArgumentException
     */
    public function createWithInvalidInputTest()
    {
        new Collection(new \stdClass());
    }

    /**
     * @test
     * @expectedException \Iresults\DateTime\Exception\InvalidArgumentException
     */
    public function createWithMixedInputTest()
    {
        new Collection(
            new TimeSlot(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            ),
            new \stdClass()
        );
    }

    /**
     * @test
     */
    public function currentTest()
    {
        $this->assertInstanceOf(TimeSlot::class, $this->fixture->current());
    }

    /**
     * @test
     */
    public function countTest()
    {
        $this->assertSame(1, $this->fixture->count());
    }

    /**
     * @test
     */
    public function nextTest()
    {
        $this->assertSame(0, $this->fixture->key());
        $this->fixture->next();
        $this->assertSame(1, $this->fixture->key());
    }

    /**
     * @test
     */
    public function keyTest()
    {
        $this->assertSame(0, $this->fixture->key());
    }

    /**
     * @test
     */
    public function validTest()
    {
        $this->assertTrue($this->fixture->valid());
        $this->fixture->next();
        $this->assertFalse($this->fixture->valid());
        $this->fixture->rewind();
        $this->assertTrue($this->fixture->valid());
    }

    /**
     * @test
     */
    public function rewindTest()
    {
        $this->fixture->next();
        $this->fixture->next();
        $this->fixture->next();
        $this->assertSame(3, $this->fixture->key());
        $this->fixture->rewind();
        $this->assertSame(0, $this->fixture->key());
    }
}
