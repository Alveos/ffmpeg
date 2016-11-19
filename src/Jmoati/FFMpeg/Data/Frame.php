<?php

namespace Jmoati\FFMpeg\Data;

class Frame extends AbstractManipulable
{
    /**
     * @var Timecode
     */
    protected $timecode;

    /**
     * @param Media    $media
     * @param Timecode $timecode
     */
    public function __construct(Media $media, Timecode $timecode)
    {
        $this->media    = $media;
        $this->timecode = $timecode;

        parent::__construct();
    }

    /**
     * @param string  $filename
     * @param boolean $accurate
     *
     * @return boolean
     */
    public function save($filename, $accurate = false)
    {
        if (false === $accurate) {
            $command = sprintf(
                '-y -ss %s -i "%s" %s -vframes 1 -f image2 "%s"',
                $this->timecode,
                $this->media->format()->getFilename(),
                (string) $this->filters(),
                $filename
            );
        } else {
            $command = sprintf(
                '-y -i "%s" %s -vframes 1 -ss %s -f image2 "%s"',
                $this->media->format()->getFilename(),
                $this->timecode,
                (string) $this->filters(),
                $filename
            );
        }

        $process = $this->media->ffmpeg()->run($command);

        return ($process->getExitCode());
    }
}
