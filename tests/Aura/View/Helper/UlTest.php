<?php
namespace Aura\View\Helper;

class UlTest extends AbstractHelperTest
{
    public function testAll()
    {
        $ul = new Ul;
        
        $actual = $ul(['id' => 'test'])
                ->items(['foo', 'bar', 'baz'])
                ->item('dib', ['class' => 'callout'])
                ->fetch();
        
        $expect = '<ul id="test">' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '    <li class="callout">dib</li>' . PHP_EOL
                . '</ul>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
        
        $actual = $ul()->items(['foo', 'bar', 'baz'])->fetch();
        $expect = '<ul>' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '</ul>' . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        $actual = $ul()->fetch();
        $expect = null;
        $this->assertSame($expect, $actual);
    }
}
