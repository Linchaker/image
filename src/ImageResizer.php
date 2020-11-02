<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;


use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;

class ImageResizer implements Resizer
{
    protected $manager;
    public function __construct()
    {
        $this->manager = new ImageManager();
    }

    public function resize($path, $size): object
    {
        try {
            $img = $this->manager->make($path);
        } catch (NotReadableException $e) {
            throw new \Exception('not an image');
        }

        switch ($img->mime()) {
            case 'image/png':
                $this->type = 'png';
                break;

            case 'image/gif':
                $this->type = 'gif';
                break;

            default:
                $this->type = 'jpg';
        }

        // valid size array - delete non numeric values
        foreach ($size as $key => $size_value) {
            if (!is_numeric($size_value)) {
                unset($size[$key]);
            }
        }

        if (!empty($size)) {
            $img->fit($size[0], $size[1] ?? $size[0]);
        }

        return $img->stream();
    }
}
