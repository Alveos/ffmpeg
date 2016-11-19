<?php

namespace Jmoati\FFMpeg\Data;

/**
 * Represents information about the container format. 
 */
class Format extends AbstractDataCollection
{
    /**
     * Returns the filename.
     * 
     * @return  string
     */
    public function getFilename()
    {
        //The 'filename' property should be defined
        return $this->get('filename');
    }

    /**
     * Returns the duration (in seconds).
     * 
     * @return  float   The duration (in seconds).
     */
    public function getDuration() 
    {
        //The 'duration' property should be defined
        return floatval($this->get('duration'));
    }
}
