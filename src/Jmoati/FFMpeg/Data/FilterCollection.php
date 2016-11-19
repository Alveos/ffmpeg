<?php

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Filter\InterfaceFilter;

class FilterCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var InterfaceFilter[]
     */
    protected $filters = array();

    /**
     * @var AbstractDataCollection
     */
    protected $parent;

    /**
     * @param AbstractManipulable $parent
     */
    public function __construct(AbstractManipulable $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return AbstractDataCollection
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @param InterfaceFilter $filter
     *
     * @return FilterCollection
     */
    public function add(InterfaceFilter $filter)
    {
        $newFilter = clone $filter;
        $newFilter->setParent($this);

        $this->filters[] = $newFilter;

        return $this;
    }

    /**
     * @param InterfaceFilter $filter
     *
     * @return FilterCollection
     */
    public function remove(InterfaceFilter $filter)
    {
        for ($i = 0, $l = count($this->filters); $i < $l; ++$i) {
            if ($this->filters[$i] === $filter) {
                unset($this->filters[$i]);
                break;
            }
        }

        return $this;
    }

    /**
     * @return FilterCollection
    */
    public function clear()
    {
        $this->filters = array();

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = array();

        foreach ($this->filters as $filter) {
            $result[] = (string) $filter;
        }

        return implode(' ', $result);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->filters);
    }

    /**
     * @return Stream[]
     */
    public function all()
    {
        return $this->filters;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->filters);
    }

    /**
     * @param integer|string $offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->filters[$offset]);
    }

    /**
     * @param integer|string $offset
     *
     * @return InterfaceFilter
     */
    public function offsetGet($offset)
    {
        return $this->filters[$offset];
    }

    /**
     * @param integer|string $offset
     * @param string         $value
     *
     * @return FilterCollection
     */
    public function offsetSet($offset, $value)
    {
        $this->filters[$offset] = $value;

        return $this;
    }

    /**
     * @param integer|string $offset
     *
     * @return FilterCollection
     */
    public function offsetUnset($offset)
    {
        unset($this->filters[$offset]);

        return $this;
    }
}
