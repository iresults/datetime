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

use Iresults\DateTime\AbstractCollection;

/**
 * Tests for the abstract collection
 *
 * @package Iresults\ResourceBooking\Tests\Unit\Domain\Model\TimeSlot
 */
class AbstractCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractCollection
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = $this->getMockBuilder(AbstractCollection::class)
            ->setMethods(array('checkItems'))
            ->setConstructorArgs(array('element1', 'element2'))
            ->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function createWithCollectionTest()
    {
        $inputCollection = $this->getMockBuilder(AbstractCollection::class)
            ->setMethods(array('checkItems'))
            ->setConstructorArgs(array('element1', 'element2', 'element3'))
            ->getMockForAbstractClass();

        $this->fixture = $this->getMockBuilder(AbstractCollection::class)
            ->setMethods(array('checkItems'))
            ->setConstructorArgs(array($inputCollection))
            ->getMockForAbstractClass();

        $this->assertSame(3, $this->fixture->count());
        $this->assertSame(array('element1', 'element2', 'element3'), $this->fixture->getArrayCopy());
    }

    /**
     * @test
     */
    public function createWithArrayTest()
    {
        $this->fixture = $this->getMockBuilder(AbstractCollection::class)
            ->setMethods(array('checkItems'))
            ->setConstructorArgs(array(array('element1', 'element2', 'element3')))
            ->getMockForAbstractClass();

        $this->assertSame(3, $this->fixture->count());
        $this->assertSame(array('element1', 'element2', 'element3'), $this->fixture->getArrayCopy());
    }

    /**
     * @test
     */
    public function currentTest()
    {
        $this->assertSame('element1', $this->fixture->current());
    }

    /**
     * @test
     */
    public function countTest()
    {
        $this->assertSame(2, $this->fixture->count());
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

    /**
     * @test
     */
    public function implodeTest()
    {
        $this->assertSame('element1element2', $this->fixture->implode());
        $this->assertSame('element1,element2', $this->fixture->implode(','));
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function offsetSetTest()
    {
        $this->fixture->offsetSet(2, 'whatever');
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function offsetSetArrayAccessTest()
    {
        $this->fixture[2] = 'whatever';
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function offsetSetWithoutIndexTest()
    {
        $this->fixture[] = 'whatever';
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function offsetUnsetTest()
    {
        $this->fixture->offsetUnset(2);
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function offsetUnsetArrayAccessTest()
    {
        unset($this->fixture[1]);
    }
}
