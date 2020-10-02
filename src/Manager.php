<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;

abstract class Manager
{
    protected $parser;
    protected $resizer;
    protected $saver;

    protected $pathToSave;

    public function __construct(Parser $parser = null, Resizer $resizer = null, Saver $saver = null)
    {
        $this->parser  = $parser ?? new ImageParser();
        $this->resizer = $resizer ?? new ImageResizer();
        $this->saver   = $saver ?? new ImageSaver();
        $this->pathToSave = '/uploads/';
    }

    abstract public function save(string $path, array $resize, string $pathToSave = null): string;
}
