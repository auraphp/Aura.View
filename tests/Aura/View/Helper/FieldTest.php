<?php
namespace Aura\View\Helper;

class FieldTest extends AbstractHelperTest
{
    protected function newField()
    {
        return new Field(
            new Input,
            new Radios(new Input),
            new Select,
            new Textarea
        );
    }
    
    public function testCheckbox()
    {
        $spec = [
            'type' => 'checkbox',
            'name' => 'field_name',
            'attr' => [
                'id' => null,
                'type' => null,
                'name' => null,
            ],
            'opts' => [
                'foo' => 'DOOM'
            ],
            'value' => 'foo',
        ];
        
        $field = $this->newField();
        $actual = $field($this->escape($spec));
        $expect = '<label><input type="checkbox" name="field_name" value="foo" checked="checked" /> DOOM</label>';
        $this->assertSame($expect, $actual);
    }
    
    public function testInput()
    {
        $spec = [
            'type' => 'text',
            'name' => 'field_name',
            'attr' => [
                'id' => null,
                'type' => null,
                'name' => null,
            ],
            'opts' => [],
            'value' => 'foo',
        ];
        
        $field = $this->newField();
        $actual = $field($this->escape($spec));
        $expect = '<input type="text" name="field_name" value="foo" />';
        $this->assertSame($expect, $actual);
    }
    
    public function testRadios()
    {
        $spec = [
            'type' => 'radios',
            'name' => 'field_name',
            'label' => null,
            'attr' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'opts' => ['opt1' => 'Label 1', 'opt2' => 'Label 2', 'opt3' => 'Label 3'],
            'value' => 'opt2',
        ];
        
        $field = $this->newField();
        $actual = $field($this->escape($spec));
        $expect = '<label><input type="radio" name="field_name" foo="bar" value="opt1" /> Label 1</label>' . PHP_EOL
                . '<label><input type="radio" name="field_name" foo="bar" value="opt2" checked="checked" /> Label 2</label>' . PHP_EOL
                . '<label><input type="radio" name="field_name" foo="bar" value="opt3" /> Label 3</label>' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testSelect()
    {
        $spec = [
            'type' => 'select',
            'name' => 'field_name',
            'label' => null,
            'attr' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'opts' => [
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
        ];
        
        $field = $this->newField();
        $actual = $field($this->escape($spec));
        
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
        $spec = [
            'type' => 'textarea',
            'name' => 'field_name',
            'label' => null,
            'attr' => [
                'id' => null,
                'type' => null,
                'name' => null,
                'foo' => 'bar',
            ],
            'opts' => ['baz' => 'dib'],
            'value' => 'Text in the textarea.',
        ];
        
        $field = $this->newField();
        $actual = $field($this->escape($spec));
        $expect = '<textarea name="field_name" foo="bar">Text in the textarea.</textarea>';
        $this->assertSame($expect, $actual);
    }
}
