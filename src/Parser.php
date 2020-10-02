<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;


interface Parser
{
    /**
     * get image by path
     * @param string $path
     * @return string stream
     */
    public function parse(string $path): string;
}
