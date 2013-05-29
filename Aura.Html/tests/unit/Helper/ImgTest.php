<?php
namespace Aura\Html\Helper;

class ImgTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $image = new Img;
        $src = '/images/example.gif';
        $actual = $image($src);
        $expect = '<img src="/images/example.gif" alt="example.gif" />';
        $this->assertSame($actual, $expect);
    }
}
