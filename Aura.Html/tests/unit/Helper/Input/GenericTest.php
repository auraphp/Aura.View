<?php
namespace Aura\Html\Helper\Input;

use Aura\Html\Helper\AbstractHelperTest;

class GenericTest extends AbstractHelperTest
{
    /**
     * @dataProvider provideTypes
     */
    public function test($type)
    {
        $input = $this->helper;
        
        // value should override attribute
        $actual = $input([
            'type'    => $type,
            'name'    => 'foo',
            'value'   => 'bar',
            'attribs' => [
                // should get overridden
                'value' => 'baz',
            ],
        ]);
        
        $expect = "<input type=\"$type\" name=\"foo\" value=\"bar\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // no value given so attribute should still be there
        $actual = $input([
            'type'     => $type,
            'name'     => 'foo',
            'attribs'  => [
                // should remain in place
                'value' => 'baz',
            ],
        ]);
        
        $expect = "<input type=\"$type\" name=\"foo\" value=\"baz\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function provideTypes()
    {
        return [
            ['button'],
            ['color'],
            ['date'],
            ['datetime'],
            ['datetime-local'],
            ['email'],
            ['file'],
            ['hidden'],
            ['image'],
            ['month'],
            ['number'],
            ['password'],
            ['range'],
            ['reset'],
            ['search'],
            ['submit'],
            ['tel'],
            ['text'],
            ['time'],
            ['url'],
            ['week'],
        ];
    }
}
