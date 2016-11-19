<?php

namespace Jmoati\FFMpeg\Data;

class Stream extends AbstractDataCollection
{
    /**
     * @return boolean
     */
    public function isAudio()
    {
        return $this->has('codec_type') ? 'audio' === $this->get('codec_type') : false;
    }

    /**
     * @return boolean
     */
    public function isVideo()
    {
        return $this->has('codec_type') ? 'video' === $this->get('codec_type') : false;
    }

    /**
     * @return boolean
     */
    public function isData()
    {
        return $this->has('codec_type') ? 'data' === $this->get('codec_type') : false;
    }

    /**
     * return boolean
     */
    public function isImage()
    {
        return 'image2' == $this->media()->format()->get('format_name');
    }
}
