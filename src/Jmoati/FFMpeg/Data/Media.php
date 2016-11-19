<?php

namespace Jmoati\FFMpeg\Data;

use Jmoati\FFMpeg\Builder\CommandBuilder;
use Jmoati\FFMpeg\FFMpeg;
use Symfony\Component\Filesystem\Filesystem;

class Media
{

    /**
     * @var StreamCollection
     */
    protected $streams;

    /**
     * @var Format
     */
    protected $format;

    /**
     * @var FFmpeg
     */
    protected $ffmpeg;
    protected $fs;

    /**
     *
     * @param FFMpeg           $ffmpeg
     * @param StreamCollection $streams
     * @param Format           $format
     */
    public function __construct(FFMpeg $ffmpeg, StreamCollection $streams = null, Format $format = null)
    {
        $this->fs     = new Filesystem();
        $this->ffmpeg = $ffmpeg;

        if (null === $streams) {
            $this->streams = new StreamCollection();
        } else {
            $this->streams = $streams;
        }

        $this->streams->setMedia($this);

        if (null === $format) {
            $this->format = new Format();
        } else {
            $this->format = $format;
        }

        $this->format->setMedia($this);
    }

    /**
     * @return StreamCollection
     */
    public function streams()
    {
        return $this->streams;
    }

    /**
     * @return Format
     */
    public function format()
    {
        return $this->format;
    }

    /**
     * @return FFMpeg
     */
    public function ffmpeg()
    {
        return $this->ffmpeg;
    }

    /**
     * @param Timecode $timecode
     *
     * @return Frame
     */
    public function frame($timecode)
    {
        return new Frame($this, $timecode);
    }

    public function getFrameCount(Output $output)
    {
        $commandBuilder = new CommandBuilder($this, $output, true);
        $frames         = 0;

        $this->ffmpeg->run(
            sprintf(
                '%s %s %s "%s" -y',
                $commandBuilder->computeInputs(),
                $commandBuilder->computeFormatFilters(),
                $commandBuilder->computeParams(),
                '/dev/null'
            ),
            function ($type, $buffer) use (&$frames) {
                if (preg_match('/frame=\s*([0-9]+)\s/', $buffer, $matches)) {
                    $frames = $matches[1];
                }
            }
        );

        return $frames + 1;
    }

    /**
     * @param string        $filename
     * @param Output        $output
     * @param null|callable $callback
     *
     * @return Media
     */
    public function save($filename, Output $output = null, $callback = null)
    {
        $commandBuilder = new CommandBuilder($this, $output);
        $tmpDir         = sys_get_temp_dir() . '/' . sha1(uniqid()) . '/';

        $this->fs->mkdir($tmpDir);

        $passes = null !== $output ? $output->getPasses() : 1;

        if (null !== $callback && property_exists($callback, 'totalPasses')) {
            $callback->totalPasses = $passes;
        }

        for ($i = 0, $l = $passes; $i < $l; ++$i) {

            if (null !== $callback && property_exists($callback, 'currentPass')) {
                $callback->currentPass = $i + 1;
            }

            if (null !== $callback && property_exists($callback, 'currentFrame')) {
                $callback->currentFrame = 0;
            }

            if (null !== $callback && property_exists($callback, 'totalFrames')) {
                $callback->totalFrames = $this->getFrameCount($output);
            }
            $this->ffmpeg->run(
                sprintf(
                    '%s %s %s %s "%s" -y',
                    $commandBuilder->computeInputs(),
                    $commandBuilder->computePasses($i, $passes, $tmpDir),
                    $commandBuilder->computeFormatFilters(),
                    $commandBuilder->computeParams(),
                    $filename
                ),
                get_class($callback) == 'Closure' || null === $callback ?
                    $callback :
                    array($callback, 'callback')
            );
        }

        $this->fs->remove($tmpDir);

        return $this;
    }
}
