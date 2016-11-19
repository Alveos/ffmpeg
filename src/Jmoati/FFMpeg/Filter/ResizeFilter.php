<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Dimension;

class ResizeFilter extends AbstractFilter implements InterfaceFormatFilter, InterfaceFrameFilter
{
    const MODE_FORCE      = 0;
    const MODE_INSET      = 1;
    const MODE_MAX_WIDTH  = 2;
    const MODE_MAX_HEIGHT = 4;

    /**
     * @var Dimension
     */
    protected $dimension;

    /**
     * @var integer
     */
    protected $mode;

    /**
     * @param Dimension $dimension
     * @param integer   $mode
     */
    public function __construct(Dimension $dimension, $mode = self::MODE_INSET)
    {
        $this->dimension = $dimension;
        $this->mode      = $mode;
    }

    /**
     * @return Dimension
     */
    protected function compute()
    {
        $source           = $this->media()->streams()->videos()->first();
        $source_dimension = new Dimension($source->get('width'), $source->get('height'));

        if (self::MODE_MAX_HEIGHT == $this->mode || (self::MODE_INSET == $this->mode && $this->dimension->getRatio() > $source_dimension->getRatio())) {
            $this->dimension->setWidth($this->dimension->getHeight() * $source_dimension->getRatio());
        } elseif (self::MODE_MAX_WIDTH == $this->mode || self::MODE_INSET == $this->mode) {
            $this->dimension->setHeight($this->dimension->getWidth() / $source_dimension->getRatio());
        }

        foreach ($this->parent() as $filter) {
            if ($filter instanceof RotationFilter && RotationFilter::ROTATION_180 != $filter->getRotation()) {
                $width = $this->dimension->getWidth();
                $this->dimension->setWidth($this->dimension->getHeight());
                $this->dimension->setHeight($width);
            }
        }

        return $this->dimension;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('-s %s', (string) $this->compute());
    }

}
