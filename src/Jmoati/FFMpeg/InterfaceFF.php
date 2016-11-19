<?php

namespace Jmoati\FFMpeg;

use Symfony\Component\Process\Process;

interface InterfaceFF
{
    /**
     * @param string        $command
     * @param callable|null $callback
     *
     * @return Process
     */
    public function run($command, $callback = null);
}
