<?php
namespace Aura\Html\Helper;

class FormTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $form = new Form;
        $actual = $form([
            'method' => 'post',
            'action' => 'http://example.com/',
        ]);
        $expect = '<form method="post" action="http://example.com/" enctype="multipart/form-data">';
        $this->assertSame($actual, $expect);
    }
}
