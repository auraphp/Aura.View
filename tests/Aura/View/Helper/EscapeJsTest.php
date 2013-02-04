<?php
namespace Aura\View\Helper;

class EscapeJsTest extends EscapeTest
{
    protected $class = 'Aura\View\Helper\EscapeJs';
    
    protected $immune = [',', '.', '_'];
    
    public function provide__invoke()
    {
        return [
            ['<'     , '\\x3C'],
            ['>'     , '\\x3E'],
            ['\''    , '\\x27'],
            ['"'     , '\\x22'],
            ['&'     , '\\x26'],
            ['Ā'     , '\\u0100'],
            [','     , ','],
            ['.'     , '.'],
            ['_'     , '_'],
            ['a'     , 'a'],
            ['A'     , 'A'],
            ['z'     , 'z'],
            ['Z'     , 'Z'],
            ['0'     , '0'],
            ['9'     , '9'],
            ["\r"    , '\\x0D'],
            ["\n"    , '\\x0A'],
            ["\t"    , '\\x09'],
            ["\0"    , '\\x00'],
            [' '     , '\\x20'],
            [''      , ''],
            ['123'   , '123']
        ];
    }
}
