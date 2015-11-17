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


namespace Iresults\DateTime;


use ArrayAccess;
use Countable;
use Iresults\DateTime\Exception\InvalidArgumentException;
use Iterator;
use Traversable;

/**
 * Abstract collection class
 *
 * @package Iresults\DateTime\TimeSlot
 */
abstract class AbstractCollection implements Iterator, Countable, ArrayAccess, ImmutableInterface
{
    /**
     * @var mixed[]
     */
    protected $collectionItems = array();

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * Collection constructor.
     *
     * @param mixed[] ...$items
     */
    public function __construct($items = null)
    {
        if ($items !== null) {
            if (func_num_args() === 1 && (is_array($items) || $items instanceof Traversable)) {
                $this->setCollectionItems($items);
            } else {
                $this->setCollectionItems(func_get_args());
            }
        }
    }

    /**
     * Make sure the items from the collection are as required
     *
     * @param mixed[] $collectionItems
     * @return void
     */
    abstract protected function checkItems($collectionItems);

    /**
     * Returns an array version
     *
     * @return mixed[]
     */
    public function getArrayCopy()
    {
        return $this->collectionItems;
    }

    /**
     * Sets the TimeSlots
     *
     * @param mixed[] $collectionItems
     */
    protected function setCollectionItems($collectionItems)
    {
        if (!is_array($collectionItems) && $collectionItems instanceof Traversable) {
            $collectionItems = iterator_to_array($collectionItems);
        } else {
            InvalidArgumentException::exceptionWithArgumentNameTypeAndValue(
                'collectionItems',
                ['array', 'Traversable'],
                $collectionItems
            );
        }
        $this->checkItems($collectionItems);
        $this->collectionItems = $collectionItems;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->offsetGet($this->index);
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->current() !== null;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *        </p>
     *        <p>
     *        The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->collectionItems);
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->offsetGet($offset) !== null;
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->collectionItems[$offset]) ? $this->collectionItems[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Method offsetSet forbidden for ImmutableInterface');
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('Method offsetUnset forbidden for ImmutableInterface');
    }

    /**
     * @param string $glue
     * @return string
     */
    public function implode($glue = '')
    {
        return implode($glue, iterator_to_array($this));
    }
}
