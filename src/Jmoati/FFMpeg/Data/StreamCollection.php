<?php

namespace Jmoati\FFMpeg\Data;

class StreamCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var Stream[]
     */
    protected $streams = array();

    /**
     * @param array $streams
     */
    public function __construct(array $streams = array())
    {
        foreach ($streams as $stream) {
            if ($stream instanceof Stream) {
                $this->add($stream);
            } else {
                $this->streams[] = new Stream($stream);
            }
        }
    }

    /**
     * @return Stream
     */
    public function first()
    {
        return reset($this->streams);
    }

    /**
     * @param Stream $stream
     *
     * @return StreamCollection
     */
    public function add(Stream $stream)
    {
        $newStream = clone $stream;
        $this->streams[] = $newStream;

        return $this;
    }

    /**
     * @param Stream $stream
     *
     * @return StreamCollection
     */
    public function remove(Stream $stream)
    {
        for ($i = 0, $l = count($this->streams); $i < $l; ++$i) {
            if ($this->streams[$i] === $stream) {
                unset($this->streams[$i]);
                break;
            }
        }

        return $this;
    }

    /**
     * @return StreamCollection
     */
    public function videos()
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isVideo();
            }
        ));
    }

    /**
     * @return StreamCollection
     */
    public function audios()
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isAudio();
            }
        ));
    }

    /**
     * @return StreamCollection
     */
    public function datas()
    {
        return new static(array_filter(
            $this->streams,
            function (Stream $stream) {
                return $stream->isData();
            }
        ));
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->streams);
    }

    /**
     * @return Stream[]
     */
    public function all()
    {
        return $this->streams;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->streams);
    }

    public function offsetExists($offset)
    {
        return isset($this->streams[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->streams[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->streams[$offset] = $value;

        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($this->streams[$offset]);

        return $this;
    }

    /**
     * @param Media $media
     *
     * @return StreamCollection
     */
    public function setMedia(Media $media)
    {
        foreach ($this->streams as $stream) {
            $stream->setMedia($media);
        }

        return $this;
    }

}
