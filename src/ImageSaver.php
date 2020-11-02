<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;

class ImageSaver implements Saver
{
    /**
     * save image to storage
     * @param $img
     * @param $pathToSave
     * @return string
     */
    public function save($img, $pathToSave): string
    {
        $fileName = $this->getHash();
        $fullFileName = $this->generatePath($fileName, $pathToSave);

        if ($this->saving($fullFileName, $img)) {
            return $fileName;
        }
        return 'false';
    }

    protected function saving($fullFileName, $img): bool
    {
        return (boolean) file_put_contents($fullFileName, $img);
    }

    /**
     * create full path to image
     * @param string $fileName
     * @param $pathToSave
     * @return string
     */
    protected function generatePath(string $fileName, $pathToSave): string
    {
        if (!is_dir($pathToSave)) {
            mkdir($pathToSave, 0755);
        }
        return $pathToSave . $fileName . '.' . 'jpg';
    }

    /**
     * generate name for image
     * @return string
     */
    protected function getHash(): string
    {
        return hash('crc32', md5((string) mt_rand()));
    }
}
