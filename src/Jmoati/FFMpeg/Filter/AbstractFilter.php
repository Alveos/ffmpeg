<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Media;

class AbstractFilter
{
    /**
     * @var FilterCollection
     */
    protected $parent;

    /**
     * @param FilterCollection $parent
     *
     * @throws \LogicException
     * @return $this
     */
    public function setParent(FilterCollection $parent)
    {
        $this->checkFilterType($parent, 'Stream', 'InterfaceStreamFilter');
        $this->checkFilterType($parent, 'Format', 'InterfaceFormatFilter');
        $this->checkFilterType($parent, 'Frame', 'InterfaceFrameFilter');

        $this->parent = $parent;

        return $this;
    }

    /**
     * @param FilterCollection $parent
     * @param string           $className
     * @param string           $interface
     *
     * @return bool
     * @throws \LogicException
     */
    protected function checkFilterType($parent, $className, $interface)
    {
        if (basename(str_replace('\\', '/', get_class($parent->parent()))) != $className) {
            return true;
        }

        foreach (class_implements($this) as $implement) {
            if ($interface == basename(str_replace('\\', '/', $implement))) {
                return true;
            }
        }

        throw new \LogicException(sprintf(
            'Filter %s can\'t be use with %s',
            basename(str_replace('\\', '/', get_class($this))),
            $className
        ));
    }

    /**
     * @return FilterCollection
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @return Media
     */
    public function media()
    {
        return $this->parent()->parent()->media();
    }
}
