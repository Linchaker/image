<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;

class ImageResizerTest extends TestCase
{
    public function setUp()
    {
        $this->imageResizer = new \Linchaker\ImagePRS\ImageResizer();
    }

    public function testCanImageResize()
    {
        $stream = $this->imageResizer->resize(__DIR__ . '/files/img.jpg', [200, 200]);

        $this->assertInstanceOf('GuzzleHttp\Psr7\Stream', $stream);

    }


    public function testFailWhenTryResizeNoImage()
    {
        try {
            $this->imageResizer->resize(__DIR__ . '/files/not-image.jpg', [200, 200]);
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'not an image');
            return;
        }

        $this->fail('testFailWhenTryResizeNoImage');
    }
}
