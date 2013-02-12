<?php
namespace Aura\View\Helper;

class UlTest extends AbstractHelperTest
{
    public function testAll()
    {
        $ul = new Ul;
        
        $actual = $ul($this->escape(['id' => 'test']))
                ->items($this->escape(['foo', 'bar', 'baz']))
                ->item($this->escape('dib'), $this->escape(['class' => 'callout']))
                ->get();
        
        $expect = '<ul id="test">' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '    <li class="callout">dib</li>' . PHP_EOL
                . '</ul>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
        
        $actual = $ul()->items($this->escape(['foo', 'bar', 'baz']))->get();
        $expect = '<ul>' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '</ul>' . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        $actual = $ul()->get();
        $expect = null;
        $this->assertSame($expect, $actual);
    }
}
