<?php
namespace Aura\View\Helper;

class EscapeCssTest extends EscapeTest
{
    protected $class = 'Aura\View\Helper\EscapeCss';
    
    protected $immune = [];
    
    public function provide__invoke()
    {
        return [
            ['<'     , '\\3C '],
            ['>'     , '\\3E '],
            ['\''    , '\\27 '],
            ['"'     , '\\22 '],
            ['&'     , '\\26 '],
            ['Ā'     , '\\100 '],
            [','     , '\\2C '],
            ['.'     , '\\2E '],
            ['_'     , '\\5F '],
            ['a'     , 'a'],
            ['A'     , 'A'],
            ['z'     , 'z'],
            ['Z'     , 'Z'],
            ['0'     , '0'],
            ['9'     , '9'],
            ["\r"    , '\\D '],
            ["\n"    , '\\A '],
            ["\t"    , '\\9 '],
            ["\0"    , '\\0 '],
            [' '     , '\\20 '],
            [''      , ''],
            ['123'   , '123'],
        ];
    }
}
