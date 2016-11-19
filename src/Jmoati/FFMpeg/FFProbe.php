<?php

namespace Jmoati\FFMpeg;

use Jmoati\FFMpeg\Data\Format;
use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\StreamCollection;
use Symfony\Component\Process\Process;

class FFProbe implements InterfaceFF
{
    const COMMAND_STREAMS = '-show_streams';
    const COMMAND_FORMAT  = '-show_format';
    const COMMAND_MEDIA   = '-show_streams -show_format';

    /**
     * @var string
     */
    protected $bin;

    public function __construct()
    {
        $proccess = new Process('which ffprobe');
        $proccess->run();

        if (0 == $proccess->getExitCode()) {
            $this->bin     = 'ffprobe';
        } else {
            $this->bin = realpath('. ' . __DIR__ . '/../../../../ffmpeg-x64-bin/ffprobe');
        }
    }

    /**
     * @return FFProbe
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $filename
     *
     * @return Format
     */
    public function format($filename)
    {
        return $this->probe($filename, self::COMMAND_FORMAT);
    }

    /**
     * @param string $filename
     *
     * @return StreamCollection
     */
    public function streams($filename)
    {
        return $this->probe($filename, self::COMMAND_STREAMS);
    }

    /**
     * @param string $filename
     *
     * @return Media
     */
    public function media($filename)
    {
        return $this->probe($filename, self::COMMAND_MEDIA);
    }

    /**
     * @param string $filename
     * @param string $command
     *
     * @return Format|StreamCollection|Media
     * @throws \Exception
     */
    protected function probe($filename, $command)
    {
        $process = $this->run(sprintf('%s -print_format json "%s"', self::COMMAND_MEDIA, $filename));

        if (0 != $process->run()) {
            throw new \Exception('File can\'t be probe.');
        }

        $output = json_decode(utf8_encode($process->getOutput()), true);
        foreach ($output['streams'] as &$stream) {
            $stream['media_filename'] = $output['format']['filename'];
        }
        unset($stream);

        switch ($command) {
            case self::COMMAND_STREAMS:
                return new StreamCollection($output['streams']);

            case self::COMMAND_FORMAT:
                return new Format($output['format']);

            case self::COMMAND_MEDIA:
                return new Media(new FFMpeg($this), new StreamCollection($output['streams']), new Format($output['format']));

            default:
                throw new \Exception('Command not found');
        }
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
