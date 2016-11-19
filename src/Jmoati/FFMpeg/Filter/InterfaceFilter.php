<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\FilterCollection;
use Jmoati\FFMpeg\Data\Media;

interface InterfaceFilter
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return Media
     */
    public function media();

    /**
     * @param FilterCollection $parent
     */
    public function setParent(FilterCollection $parent);

    /**
     * @return FilterCollection
     */
    public function parent();

}
