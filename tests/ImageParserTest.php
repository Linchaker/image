<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;

class ImageParserTest extends TestCase
{
    public function setUp()
    {
        $this->imageParser = new \Linchaker\ImagePRS\ImageParser();
    }

    public function pathDataProvider()
    {
        return [
            [
                __DIR__ . '/files/img.jpg',
                file_get_contents(__DIR__ . '/files/img.jpg')
            ],
            [
                'https://www.hdwallpapers.in/hailee_steinfeld_2018-wallpapers.html',
                file_get_contents('https://www.hdwallpapers.in/download/hailee_steinfeld_2018-wide.jpg')
            ],
        ];
    }

    /**
     * @dataProvider pathDataProvider
     */
    public function testParse($path, $expected)
    {
        $result = $this->imageParser->parse($path);

        $this->assertEquals($expected, $result);
    }

}
