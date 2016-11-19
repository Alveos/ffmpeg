<?php

namespace Jmoati\FFMpeg\Data;

abstract class AbstractDataCollection extends AbstractManipulable implements \Countable
{
    /**
     * @param string[] $properties
     */
    protected $properties;

    /**
     * @param string[] $properties
     */
    public function __construct(array $properties = array())
    {
        $this->properties = $properties;

        parent::__construct();
    }

    /**
     * @param string $property
     *
     * @return boolean
     */
    public function has($property)
    {
        return isset($this->properties[$property]);
    }

    /**
     * @param string $property
     *
     * @return string
     */
    public function get($property)
    {
        if (!isset($this->properties[$property])) {
            return null;
        }

        return $this->properties[$property];
    }

    /**
     * @return integer[]
     */
    public function keys()
    {
        return array_keys($this->properties);
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return $this->properties;
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->properties);
    }
}
