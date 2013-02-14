<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class InputTest extends AbstractHelperTest
{
    
    protected function newInput()
    {
        return new Input([
            'button'         => function () { return new Input\Button; },
            'checkbox'       => function () { return new Input\Checked; },
            'color'          => function () { return new Input\Value; },
            'date'           => function () { return new Input\Value; },
            'datetime'       => function () { return new Input\Value; },
            'datetime-local' => function () { return new Input\Value; },
            'email'          => function () { return new Input\Value; },
            'file'           => function () { return new Input\Button; },
            'hidden'         => function () { return new Input\Value; },
            'image'          => function () { return new Input\Button; },
            'month'          => function () { return new Input\Value; },
            'number'         => function () { return new Input\Value; },
            'password'       => function () { return new Input\Value; },
            'radio'          => function () { return new Input\Checked; },
            'range'          => function () { return new Input\Value; },
            'reset'          => function () { return new Input\Button; },
            'search'         => function () { return new Input\Value; },
            'submit'         => function () { return new Input\Button; },
            'tel'            => function () { return new Input\Value; },
            'text'           => function () { return new Input\Value; },
            'time'           => function () { return new Input\Value; },
            'url'            => function () { return new Input\Value; },
            'week'           => function () { return new Input\Value; },
        ]);
    }
    
    /**
     * @dataProvider provideValueTypes
     */
    public function testValueTypes($type)
    {
        $input = $this->newInput();
        
        // given value should override the attribute
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'should not be here',
            ]),
            $this->escape('field value')
        );
        
        $expect = "<input type=\"$type\" value=\"field value\" />";
        $this->assertSame($expect, $actual);
        
        // no value given so attribute should still be there
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'field value',
            ])
        );
        
        $expect = "<input type=\"$type\" value=\"field value\" />";
        $this->assertSame($expect, $actual);
    }
    
    /**
     * @dataProvider provideCheckedTypes
     */
    public function testCheckedTypes($type)
    {
        $input = $this->newInput();
        
        // value should be checked
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
            ]),
            $this->escape('yes')
        );
        
        $expect = "<input type=\"$type\" value=\"yes\" checked=\"checked\" />";
        
        $this->assertSame($expect, $actual);

        // value should not be checked
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
            ]),
            $this->escape('no')
        );
        
        $expect = "<input type=\"$type\" value=\"yes\" />";
    }
    
    /**
     * @dataProvider provideButtonTypes
     */
    public function testButtonTypes($type)
    {
        $input = $this->newInput();
        
        // given value should not override the attribute
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'button value',
            ]),
            $this->escape('should not be here')
        );
        
        $expect = "<input type=\"$type\" value=\"button value\" />";
        
        $this->assertSame($expect, $actual);
    }
    
    public function testLabelWithAttribs()
    {
        $input = $this->newInput();
        
        $actual = $input(
            $this->escape([
                'type' => 'radio',
                'value' => 'yes',
                'id' => 'radio-yes'
            ]),
            $this->escape('no'),
            $this->escape('Radio Label'),
            $this->escape([
                'class' => 'test',
            ])
        );
        
        $expect = '<label class="test" for="radio-yes">'
                . '<input type="radio" value="yes" id="radio-yes" /> Radio Label</label>';
        
        $this->assertSame($expect, $actual);
    }

    public function testLabelWithoutAttribs()
    {
        $input = $this->newInput();
        
        $actual = $input(
            $this->escape([
                'type' => 'radio',
                'value' => 'yes',
            ]),
            $this->escape('no'),
            $this->escape('Radio Label')
        );
        
        $expect = '<label><input type="radio" value="yes" /> Radio Label</label>';
        
        $this->assertSame($expect, $actual);
    }
    
    public function provideValueTypes()
    {
        return [
            ['color'],
            ['date'],
            ['datetime'],
            ['datetime-local'],
            ['email'],
            ['hidden'],
            ['month'],
            ['number'],
            ['password'],
            ['range'],
            ['search'],
            ['tel'],
            ['text'],
            ['time'],
            ['url'],
            ['week'],
        ];
    }

    public function provideCheckedTypes()
    {
        return [
            ['checkbox'],
            ['radio'],
        ];
    }

    public function provideButtonTypes()
    {
        return [
            ['button'],
            ['file'],
            ['image'],
            ['reset'],
            ['submit'],
        ];
    }

}

