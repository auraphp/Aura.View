<?php
namespace Aura\Html\Helper;

class TagTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $tag = new Tag;
        
        $actual = $tag('form', [
            'action' => '/action.php',
            'method' => 'post',
        ]);
        
        $expect = '<form action="/action.php" method="post">';
        
        $this->assertSame($expect, $actual);
        
        $actual = $tag('div');
        $expect = '<div>';
        $this->assertSame($expect, $actual);
    }
}
