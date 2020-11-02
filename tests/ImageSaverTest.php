<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Linchaker\ImagePRS\ImageSaver;

class ImageSaverTest extends TestCase
{
    public function setUp()
    {
        $this->imageSaver = new ImageSaver();
    }

    public function testImageCanBeSaved()
    {
        $saver = $this->getMockBuilder('\Linchaker\ImagePRS\ImageSaver')
            ->setMethods(array('saving'))
            ->getMock();

        $saver->expects($this->once())
            ->method('saving')
            ->will($this->returnValue(true));

        $fileName = $saver->save(__DIR__ . '/files/img.jpg', __DIR__ . '/files/temp/');

        $this->assertNotEquals('false', $fileName);
    }

    public function testImageCanNotBeSaved()
    {
        $saver = $this->getMockBuilder('\Linchaker\ImagePRS\ImageSaver')
            ->setMethods(array('saving'))
            ->getMock();

        $saver->expects($this->any())
            ->method('saving')
            ->will($this->returnValue(false));

        $fileName = $saver->save(__DIR__ . '/files/img.jpg', __DIR__ . '/files/temp/');

        $this->assertEquals('false', $fileName);
    }

}
