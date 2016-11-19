<?php

namespace Jmoati\FFMpeg\Data;

class Output
{
    /**
     * @var integer
     */
    protected $audioKiloBitrate;
    /**
     * @var string
     */
    protected $audioCodec;

    /**
     * @var integer
     */
    protected $videoKiloBitrate;
    /**
     * @var string
     */
    protected $videoCodec;

    /**
     * @var string
     */
    protected $format;
    /**
     * @var integer
     */
    protected $passes = 1;
    /**
     * @var string[]
     */
    protected $extraParams = array();

    /**
     * @param string $audioCodec
     *
     * @return Output
     */
    public function setAudioCodec($audioCodec)
    {
        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * @return string
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * @param integer $audioKiloBitrate
     *
     * @return Output
     */
    public function setAudioKiloBitrate($audioKiloBitrate)
    {
        $this->audioKiloBitrate = $audioKiloBitrate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAudioKiloBitrate()
    {
        return $this->audioKiloBitrate;
    }

    /**
     * @param string[] $extraParams
     *
     * @return Output
     */
    public function setExtraParams($extraParams)
    {
        $this->extraParams = $extraParams;

        return $this;
    }

    public function addExtraParam($param, $value = null)
    {
        $this->extraParams[$param] = $value;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExtraParams()
    {
        return $this->extraParams;
    }

    /**
     * @param string $format
     *
     * @return Output
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param integer $passes
     *
     * @return Output
     */
    public function setPasses($passes)
    {
        $this->passes = $passes;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPasses()
    {
        return $this->passes;
    }

    /**
     * @param string $videoCodec
     *
     * @return Output
     */
    public function setVideoCodec($videoCodec)
    {
        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return string
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * @param integer $videoKiloBitrate
     *
     * @return Output
     */
    public function setVideoKiloBitrate($videoKiloBitrate)
    {
        $this->videoKiloBitrate = $videoKiloBitrate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getVideoKiloBitrate()
    {
        return $this->videoKiloBitrate;
    }

    /**
     * @return Output
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return string[]
     */
    public function getParams()
    {
        $params = $this->extraParams;

        if (null !== $this->getAudioCodec()) {
            $params['acodec'] = $this->getAudioCodec();
        }

        if (null !== $this->getAudioKiloBitrate()) {
            $params['b:a'] = $this->getAudioKiloBitrate() . 'K';
        }

        if (null !== $this->getFormat()) {
            $params['f'] = $this->getFormat();
        }

        if (null !== $this->getVideoCodec()) {
            $params['vcodec'] = $this->getVideoCodec();
        }

        if (null !== $this->getVideoKiloBitrate()) {
            $params['b:v'] = $this->getVideoKiloBitrate() . 'K';
        }

        return $params;
    }
}
