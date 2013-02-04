<?php
namespace Aura\View\Helper;

class EscapeAttrTest extends EscapeTest
{
    protected $class = 'Aura\View\Helper\EscapeAttr';
    
    protected $immune = [',', '.', '-', '_'];

    public function provide__invoke()
    {
        return [
            ['\'', '&#x27;'],
            ['"' , '&quot;'],
            ['<' , '&lt;'],
            ['>' , '&gt;'],
            ['&' , '&amp;'],
            ['Ā' , '&#x0100;'],
            [',' , ','],
            ['.' , '.'],
            ['-' , '-'],
            ['_' , '_'],
            ['a' , 'a'],
            ['A' , 'A'],
            ['z' , 'z'],
            ['Z' , 'Z'],
            ['0' , '0'],
            ['9' , '9'],
            ["\r", '&#x0D;'],
            ["\n", '&#x0A;'],
            ["\t", '&#x09;'],
            ["\0", '&#xFFFD;'],
            ['<' , '&lt;'],
            ['>' , '&gt;'],
            ['&' , '&amp;'],
            ['"' , '&quot;'],
            [' ' , '&#x20;'],
            [''  , ''],
            ['123', '123'],
        ];
    }
}
