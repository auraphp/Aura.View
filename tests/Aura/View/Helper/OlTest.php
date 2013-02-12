<?php
namespace Aura\View\Helper;

class OlTest extends AbstractHelperTest
{
    public function testAll()
    {
        $ol = new Ol;
        
        $actual = $ol($this->escape(['id' => 'test']))
                ->items($this->escape(['foo', 'bar', 'baz']))
                ->item(
                    $this->escape('dib'),
                    $this->escape(['class' => 'callout'])
                )
                ->get();
        
        $expect = '<ol id="test">' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '    <li class="callout">dib</li>' . PHP_EOL
                . '</ol>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
        
        $actual = $ol()->items($this->escape(['foo', 'bar', 'baz']))->get();
        $expect = '<ol>' . PHP_EOL
                . '    <li>foo</li>' . PHP_EOL
                . '    <li>bar</li>' . PHP_EOL
                . '    <li>baz</li>' . PHP_EOL
                . '</ol>' . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        $actual = $ol()->get();
        $expect = null;
        $this->assertSame($expect, $actual);
    }
}
