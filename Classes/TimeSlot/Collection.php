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
 * Created 15.09.15 15:35
 */


namespace Iresults\DateTime\TimeSlot;


use Iresults\DateTime\AbstractCollection;
use Iresults\DateTime\Exception\InvalidArgumentException;

/**
 * Collection of TimeSlots
 *
 * @package Iresults\DateTime\TimeSlot
 * @method __construct(TimeSlotInterface ...$items)
 * @method TimeSlotInterface offsetGet($offset)
 */
class Collection extends AbstractCollection
{
    /**
     * Make sure the items from the collection are as required
     *
     * @param mixed[] $collectionItems
     * @return void
     */
    protected function checkItems($collectionItems)
    {
        if (count($collectionItems) < 1) {
            throw new \OutOfBoundsException(
                'Argument "timeSlots" must not be empty',
                1442242463
            );
        }
        foreach ($collectionItems as $timeSlot) {
            if (!$timeSlot instanceof TimeSlotInterface) {
                throw InvalidArgumentException::exceptionWithArgumentNameTypeAndValue('timeSlot', TimeSlotInterface::class, $timeSlot);
            }
        }
    }

    /**
     * Returns if one of the Time Slots in the collection overlaps the given Time Slot
     *
     * @param TimeSlotInterface $timeSlot
     * @return bool
     */
    public function overlapsTimeSlot(TimeSlotInterface $timeSlot)
    {
        /** @var TimeSlotInterface $currentTimeSlot */
        foreach ($this as $currentTimeSlot) {
            if ($currentTimeSlot->overlapsTimeSlot($timeSlot)) {
                return true;
            }
        }

        return false;
    }
}
