<?php
namespace Aura\View\Helper;

class EscapeTest extends AbstractHelperTest
{
    protected $class = 'Aura\View\Helper\Escape';
    
    protected function newInstance()
    {
        $class = $this->class;
        return new $class;
    }
    
    /**
     * @dataProvider provide__invoke
     */
    public function test__invoke($text, $expect)
    {
        $escape = $this->newInstance();
        $actual = $escape($text);
        $this->assertSame($expect, $actual);
    }
    
    public function provide__invoke()
    {           
        return [
            ['\'',  '&#039;'],
            ['"',   '&quot;'],
            ['<',   '&lt;'],
            ['>',   '&gt;'],
            ['&',   '&amp;'],
        ];
    }
    
    public function testOwaspRange()
    {
        if (! isset($this->immune)) {
            $this->assertTrue(true);
            return;
        }
        
        $escape = $this->newInstance();
        
        for ($chr=0; $chr < 0xFF; $chr++) {
            if ($chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $literal = $this->codepointToUtf8($chr);
                $this->assertEquals($literal, $escape($literal));
            } else {
                $literal = $this->codepointToUtf8($chr);
                if (in_array($literal, $this->immune)) {
                    $this->assertEquals($literal, $escape($literal));
                } else {
                    $this->assertNotEquals(
                        $literal,
                        $escape($literal),
                        $literal . ' should be escaped!'
                    );
                }
            }
        }
    }
    
    /**
     * Convert a Unicode Codepoint to a literal UTF-8 character.
     *
     * @param int Unicode codepoint in hex notation
     * @return string UTF-8 literal string
     */
    protected function codepointToUtf8($codepoint)
    {
        if ($codepoint < 0x80) {
            return chr($codepoint);
        }
        
        if ($codepoint < 0x800) {
            return chr($codepoint >> 6 & 0x3f | 0xc0)
                . chr($codepoint & 0x3f | 0x80);
        }
        
        if ($codepoint < 0x10000) {
            return chr($codepoint >> 12 & 0x0f | 0xe0)
                 . chr($codepoint >> 6 & 0x3f | 0x80)
                 . chr($codepoint & 0x3f | 0x80);
        }
        
        if ($codepoint < 0x110000) {
            return chr($codepoint >> 18 & 0x07 | 0xf0)
                 . chr($codepoint >> 12 & 0x3f | 0x80)
                 . chr($codepoint >> 6 & 0x3f | 0x80)
                 . chr($codepoint & 0x3f | 0x80);
        }
        
        throw new \Exception('Codepoint requested outside of Unicode range');
    }
}
