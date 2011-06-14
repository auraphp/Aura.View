<?php
namespace Aura\View\Helper;

class EscapeTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $e = new Escape;
        
        $raw = '<\'single\' "double">';
        $expect = '&lt;&#039;single&#039; &quot;double&quot;&gt;';
        $actual = $e($raw);
        $this->assertSame($expect, $actual);
        
        $e->setEscapeQuotes(ENT_COMPAT);
        $raw = '<\'single\' "double">';
        $expect = '&lt;\'single\' &quot;double&quot;&gt;';
        $actual = $e($raw);
        $this->assertSame($expect, $actual);
        
        // should add some alternative chars here (like euro sign)
        $e->setEscapeCharset('ISO-8859-15');
        $raw = '<\'single\' "double">';
        $expect = '&lt;\'single\' &quot;double&quot;&gt;';
        $actual = $e($raw);
        $this->assertSame($expect, $actual);
    }
}
