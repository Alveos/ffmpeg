<?php

namespace Jmoati\FFMpeg\Builder;

use Jmoati\FFMpeg\Data\Media;
use Jmoati\FFMpeg\Data\Output;

class CommandBuilder
{
    /**
     * @var Media
     */
    protected $media;

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var string[]
     */
    protected $files = array();

    /**
     * @var string[]
     */
    protected $params = array();

    protected $dryRun;

    /**
     * @param Media   $media
     * @param Output  $output
     * @param boolean $dry
     */
    public function __construct(Media $media, Output $output = null, $dryRun = false)
    {
        $this->media  = $media;
        $this->output = $output;
        $this->dryRun    = $dryRun;
    }

    /**
     * @return string
     */
    public function computeInputs()
    {
        $result = array();

        foreach ($this->media->streams() as $stream) {
            $result[] = (string) $stream->filters();

            if ('image2' == $stream->media()->format()->get('format_name'))            {
                $result[] = '-loop 1';
            }

            $result[] = sprintf('-i "%s"', $stream->get('media_filename'));
        }

        foreach ($this->media->streams() as $index => $stream) {
            $result[] = sprintf("-map %s:%s", $index, $stream->get('index'));
        }

        return implode(' ', $result);
    }

    public function computePasses($i, $total, $tmpDir)
    {
        return 1 === $total ? '' : sprintf(
            '-pass %d -passlogfile %s',
            $i + 1,
            $tmpDir
        );
    }

    /**
     * @return string
     */
    public function computeFormatFilters()
    {
        return (string) $this->media->format()->filters();
    }

    public function computeParams()
    {
        if (null === $this->output) {
            return '';
        }

        $result = array();

        $params = $this->output->getParams();

        if (true === $this->dryRun) {
            $params['acodec'] = 'copy';
            $params['vcodec'] = 'copy';
            $params['f']      = 'avi';
        }

        foreach ($params as $param => $value) {
            $result[] = "-$param $value";
        };

        return implode(" ", $result);
    }
}
