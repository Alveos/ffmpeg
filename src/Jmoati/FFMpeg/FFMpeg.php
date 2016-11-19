<?php

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Media;
use Symfony\Component\Process\Process;

class FFMpeg implements InterfaceFF
{
    /**
     * @var FFProbe
     */
    protected $ffprobe;

    /**
     * @var string
     */
    protected $bin;

    /**
     * @param FFProbe $ffprobe
     *
     * @return FFMpeg
     */
    public static function create(FFProbe $ffprobe = null)
    {
        if (null === $ffprobe) {
            $ffprobe = new FFProbe();
        }

        return new static($ffprobe);
    }

    /**
     * @param FFProbe $ffprobe
     */
    public function __construct(FFProbe $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        $proccess = new Process('which ffmpeg');
        $proccess->run();

        if (0 == $proccess->getExitCode()) {
            $this->bin     = 'ffmpeg';
        } else {
            $this->bin = realpath(__DIR__ . '/../../../../ffmpeg-x64-bin/ffmpeg');
        }
    }

    /**
     * @return Media
     */
    public static function createFile()
    {
        $ffmpeg = self::create();

        return new Media($ffmpeg);
    }

    /**
     * @param string $filename
     *
     * @return Media
     */
    public static function openFile($filename)
    {
        return self::create()->ffprobe->media($filename);
    }

    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run($command, $callback = null)
    {
        $process = new Process($this->bin . ' ' . $command, null, null, null, 0);
        $process->run($callback);

        return $process;
    }
}
