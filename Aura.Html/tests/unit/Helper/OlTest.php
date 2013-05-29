<?php
namespace Aura\Html\Helper;

class OlTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $ol = new Ol;
        
        $actual = $ol(['id' => 'test'])
                ->items(['foo', 'bar', 'baz'])
                ->item('dib', ['class' => 'callout'])
                ->exec();
        
        $expect = '<ol id="test">' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '    <li class="callout">dib</li>' . PHP_EOL
                . '</ol>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
        
        $actual = $ol()->items(['foo', 'bar', 'baz'])->exec();
        $expect = '<ol>' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '</ol>' . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        $actual = $ol()->exec();
        $expect = null;
        $this->assertSame($expect, $actual);
    }
}
