<?php
namespace Aura\Html\Helper;

class AnchorTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $data = (object) [
            'href' => '/path/to/script.php',
            'text' => 'this',
        ];
        
        $anchor = new Anchor;
        $actual = $anchor($data->href, $data->text);
        $expect = '<a href="/path/to/script.php">this</a>';
        $this->assertSame($expect, $actual);
    }
    
    public function testWithAttribs()
    {
        $data = (object) [
            'href' => '/path/to/script.php',
            'text' => 'foo',
            'attribs' => ['bar' => 'baz', 'href' => 'skip-me'],
        ];
        
        $anchor = new Anchor;
        $actual = $anchor($data->href, $data->text, $data->attribs);
        $expect = '<a href="/path/to/script.php" bar="baz">foo</a>';
        $this->assertSame($expect, $actual);
    }
}
