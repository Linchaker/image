<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;


interface Saver
{
    public function save($img, $pathToSave): string;
}
