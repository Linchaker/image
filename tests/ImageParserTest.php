<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;

class ImageParserTest extends TestCase
{
    public function setUp()
    {
        $this->imageParser = new \Linchaker\ImagePRS\ImageParser();
    }

    protected function getPath()
    {
        return [
            'image'  => __DIR__ . '/files/img.jpg',
            'page'   => 'https://www.hdwallpapers.in/hailee_steinfeld_2018-wallpapers.html',
            'imageLinkWithoutExtension'   => 'http://lorempixel.com/500/500/',
            'imageLink' => 'https://www.hdwallpapers.in/download/hailee_steinfeld_2018-wide.jpg',
        ];
    }
    public function testParseImageLinkIsEqual()
    {
        $imagePath = $this->getPath()['image'];
        $result = $this->imageParser->parse($imagePath);

        $this->assertEquals(file_get_contents($imagePath), $result);
    }

    public function testParseImageIsNotEqualIfImagePathWithOutExtension()
    {
        $imagePath = $this->getPath()['imageLinkWithoutExtension'];
        $result = $this->imageParser->parse($imagePath);

        $this->assertNotEquals('false', $result);
    }

    public function testParsePageIsEqualIfImageFound()
    {
        $pagePath = $this->getPath()['page'];
        $mustBeFoundImage = $this->getPath()['imageLink'];
        $result = $this->imageParser->parse($pagePath);

        $this->assertEquals(file_get_contents($mustBeFoundImage), $result);
    }

}
