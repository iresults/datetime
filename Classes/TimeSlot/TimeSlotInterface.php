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
 * Created 16.06.15 15:28
 */

namespace Iresults\DateTime\TimeSlot;

use Iresults\DateTime\DateRangeInterface;

/**
 * Definition of a period in which a Resource can be reserved
 *
 * @package Iresults\DateTime\TimeSlot
 */
interface TimeSlotInterface extends DateRangeInterface
{
    /**
     * Returns the Time Slot's start
     *
     * @return \DateTime
     */
    public function getStart();

    /**
     * Returns the Time Slot's end
     *
     * @return \DateTime
     */
    public function getEnd();

    /**
     * Returns if this Time Slot overlaps the given one
     *
     * @param TimeSlotInterface $timeSlot2
     * @return bool
     */
    public function overlapsTimeSlot(TimeSlotInterface $timeSlot2);
}