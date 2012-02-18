<?php
namespace Aura\View\Helper;
use Aura\View\EscaperFactory;

class EscapeTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $escape = new Escape(new EscaperFactory);
        $actual = $escape("<foo>&bar'baz\"");
        $expect = "&lt;foo&gt;&amp;bar&#039;baz&quot;";
        $this->assertSame($expect, $actual);
    }
}
