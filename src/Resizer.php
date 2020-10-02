<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;


interface Resizer
{
    /**
     * @param $path
     * @param $size
     * @return object stream
     */
    public function resize($path, $size): object;
}
