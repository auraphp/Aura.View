<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class RepeatTest extends AbstractHelperTest
{
    protected function newRepeat()
    {
        return new Repeat([
            'button'         => function () { return new Input\Generic; },
            'checkbox'       => function () { return new Input\Checked; },
            'color'          => function () { return new Input\Value; },
            'date'           => function () { return new Input\Value; },
            'datetime'       => function () { return new Input\Value; },
            'datetime-local' => function () { return new Input\Value; },
            'email'          => function () { return new Input\Value; },
            'file'           => function () { return new Input\Generic; },
            'hidden'         => function () { return new Input\Value; },
            'image'          => function () { return new Input\Generic; },
            'month'          => function () { return new Input\Value; },
            'number'         => function () { return new Input\Value; },
            'password'       => function () { return new Input\Value; },
            'radio'          => function () { return new Input\Checked; },
            'range'          => function () { return new Input\Value; },
            'reset'          => function () { return new Input\Generic; },
            'search'         => function () { return new Input\Value; },
            'submit'         => function () { return new Input\Generic; },
            'tel'            => function () { return new Input\Value; },
            'text'           => function () { return new Input\Value; },
            'time'           => function () { return new Input\Value; },
            'url'            => function () { return new Input\Value; },
            'week'           => function () { return new Input\Value; },
            'radios'         => function () { return new Radios(new Input\Checked); },
            'select'         => function () { return new Select; },
            'textarea'       => function () { return new Textarea; },
        ]);
    }
    
    public function testAll()
    {
        $spec = $this->escape([
            'type' => null,
            'name' => 'field_name',
            'attribs' => [
                'id' => 'field_id',
                'type' => null,
                'name' => null,
            ],
            'options' => [],
            'value' => [
                '1' => 'foo',
                '2' => 'bar',
                '5' => 'baz',
                '3' => 'dib',
            ],
        ]);
        
        $repeat = $this->newRepeat();
        $actual = $repeat($spec);
        $expect = '<input id="field_id-1" type="text" name="field_name[1]" value="foo" />' . PHP_EOL
                . '<input id="field_id-2" type="text" name="field_name[2]" value="bar" />' . PHP_EOL
                . '<input id="field_id-5" type="text" name="field_name[5]" value="baz" />' . PHP_EOL
                . '<input id="field_id-3" type="text" name="field_name[3]" value="dib" />' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
}
