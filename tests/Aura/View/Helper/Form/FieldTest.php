<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class FieldTest extends AbstractHelperTest
{
    protected function newField()
    {
        return new Field([
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
    
    public function testCheckbox()
    {
        $spec = $this->escape([
            'type' => 'checkbox',
            'name' => 'field_name',
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'value' => 'foo',
                'label' => 'DOOM',
            ],
            'value' => 'foo',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        $expect = '<label><input type="checkbox" name="field_name" value="foo" checked="checked" /> DOOM</label>' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testInput()
    {
        $spec = $this->escape([
            'type' => 'text',
            'name' => 'field_name',
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
            ],
            'options' => [],
            'value' => 'foo',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        $expect = '<input type="text" name="field_name" value="foo" />' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testNoType()
    {
        $spec = $this->escape([
            'name' => 'field_name',
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
            ],
            'options' => [],
            'value' => 'foo',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        $expect = '<input type="text" name="field_name" value="foo" />' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testRadios()
    {
        $spec = $this->escape([
            'type' => 'radios',
            'name' => 'field_name',
            'label' => null,
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'options' => ['opt1' => 'Label 1', 'opt2' => 'Label 2', 'opt3' => 'Label 3'],
            'value' => 'opt2',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        $expect = '<label><input type="radio" name="field_name" foo="bar" value="opt1" /> Label 1</label>' . PHP_EOL
                . '<label><input type="radio" name="field_name" foo="bar" value="opt2" checked="checked" /> Label 2</label>' . PHP_EOL
                . '<label><input type="radio" name="field_name" foo="bar" value="opt3" /> Label 3</label>' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testSelect()
    {
        $spec = $this->escape([
            'type' => 'select',
            'name' => 'field_name',
            'label' => null,
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'options' => [
                'opt1' => 'Label 1',
                'opt2' => 'Label 2',
                'opt3' => 'Label 3',
                'Group A' => [
                    'opt4' => 'Label 4',
                    'opt5' => 'Label 5',
                    'opt6' => 'Label 6',
                ],
                'Group B' => [
                    'opt7' => 'Label 7',
                    'opt8' => 'Label 8',
                    'opt9' => 'Label 9',
                ],
            ],
            'value' => 'opt5',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        
        $expect = '<select name="field_name" foo="bar">' . PHP_EOL
                . '    <option value="opt1">Label 1</option>' . PHP_EOL
                . '    <option value="opt2">Label 2</option>' . PHP_EOL
                . '    <option value="opt3">Label 3</option>' . PHP_EOL
                . '    <optgroup label="Group A">' . PHP_EOL
                . '        <option value="opt4">Label 4</option>' . PHP_EOL
                . '        <option value="opt5" selected="selected">Label 5</option>' . PHP_EOL
                . '        <option value="opt6">Label 6</option>' . PHP_EOL
                . '    </optgroup>' . PHP_EOL
                . '    <optgroup label="Group B">' . PHP_EOL
                . '        <option value="opt7">Label 7</option>' . PHP_EOL
                . '        <option value="opt8">Label 8</option>' . PHP_EOL
                . '        <option value="opt9">Label 9</option>' . PHP_EOL
                . '    </optgroup>' . PHP_EOL
                . '</select>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
    }
    
    public function testTextarea()
    {
        $spec = $this->escape([
            'type' => 'textarea',
            'name' => 'field_name',
            'label' => null,
            'attribs' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'options' => ['baz' => 'dib'],
            'value' => 'Text in the textarea.',
        ]);
        
        $field = $this->newField();
        $actual = $field($spec);
        $expect = '<textarea name="field_name" foo="bar">Text in the textarea.</textarea>';
        $this->assertSame($expect, $actual);
    }
}
