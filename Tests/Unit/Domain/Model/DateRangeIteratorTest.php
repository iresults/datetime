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


namespace Iresults\ResourceBooking\Tests\Unit\Domain\Model;


use Iresults\DateTime\DateRange;
use Iresults\DateTime\DateRangeIterator;
use Iresults\DateTime\TimeSlot\TimeSlot;

class DateRangeIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateRangeIterator
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new DateRangeIterator(
            new DateRange(
                new \DateTime('Mon 14.09.2015 12:00'),
                new \DateTime('Mon 14.09.2015 13:00')
            ),
            DateRange::UNIT_HOUR
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
