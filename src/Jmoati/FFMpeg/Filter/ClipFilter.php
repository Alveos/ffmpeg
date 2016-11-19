<?php

namespace Jmoati\FFMpeg\Filter;

use Jmoati\FFMpeg\Data\Timecode;

class ClipFilter extends AbstractFilter implements InterfaceFormatFilter, InterfaceStreamFilter
{
    /**
     * @var Timecode
     */
    protected $start;

    /**
     * @var Timecode
     */
    protected $duration;

    /**
     * @param Timecode $start
     * @param Timecode $duration
     */
    public function __construct(Timecode $start = null, Timecode $duration = null)
    {
        $this->start    = $start;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = array();

        if (null !== $this->start) {
            $result[] = sprintf('-ss %s', $this->start);
        }

        if (null !== $this->duration) {
            $result[] = sprintf('-t %s', $this->duration);
        }

        return implode(' ', $result);
    }
}
