<?php
namespace Aura\View\Helper;

class EscapeTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $escape = new Escape;
        $actual = $escape("<foo>&bar'baz\"");
        $expect = "&lt;foo&gt;&amp;bar&#039;baz&quot;";
        $this->assertSame($expect, $actual);
    }
}
