<?php
namespace Aura\View\Helper;

class TagTest extends AbstractHelperTest
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
