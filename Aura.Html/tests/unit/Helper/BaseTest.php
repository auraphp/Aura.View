<?php
namespace Aura\Html\Helper;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $base = new Base;
        $base->setIndentLevel(1);
        $href = '/path/to/base';
        $actual = $base($href);
        $expect = '        <base href="/path/to/base" />' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
}
