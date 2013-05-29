<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class InputTest extends AbstractHelperTest
{
    
    protected function newInput()
    {
        return new Input([
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
        ]);
    }
    
    public function testNoType()
    {
        $input = $this->newInput();
        
        // given value should override the attribute
        $actual = $input(
            $this->escape([
                'type' => null,
                'value' => 'should not be here',
            ]),
            $this->escape('field value')
        );
        
        $expect = "<input type=\"text\" value=\"field value\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // no value given so attribute should still be there
        $actual = $input(
            $this->escape([
                'type' => null,
                'value' => 'field value',
            ])
        );
        
        $expect = "<input type=\"text\" value=\"field value\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
    
    public function testInputValueGetFieldNoType()
    {
        $input = new Input\Value;
        $actual = $input->getField([]);
        $expect = '<input type="text" />' . PHP_EOL;
        $this->assertSame($expect, $actual);
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
        
        $expect = "<input type=\"$type\" value=\"field value\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // no value given so attribute should still be there
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'field value',
            ])
        );
        
        $expect = "<input type=\"$type\" value=\"field value\" />" . PHP_EOL;
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
                'label' => 'This is yes'
            ]),
            $this->escape('yes')
        );
        $expect = "<label><input type=\"$type\" value=\"yes\" checked=\"checked\" /> This is yes</label>" . PHP_EOL;
        $this->assertSame($expect, $actual);

        // value should not be checked
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
                'label' => 'This is yes'
            ]),
            $this->escape('no')
        );
        $expect = "<label><input type=\"$type\" value=\"yes\" /> This is yes</label>" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // value should be checked, with unchecked value available
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
                'value_unchecked' => 'no',
                'label' => 'This is yes'
            ]),
            $this->escape('yes')
        );
        $expect = "<label><input type=\"hidden\" value=\"no\" /><input type=\"$type\" value=\"yes\" checked=\"checked\" /> This is yes</label>" . PHP_EOL;
        $this->assertSame($expect, $actual);

        // value should not be checked, with unchecked value available
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
                'value_unchecked' => 'no',
                'label' => 'This is yes'
            ]),
            $this->escape('no')
        );
        $expect = "<label><input type=\"hidden\" value=\"no\" /><input type=\"$type\" value=\"yes\" /> This is yes</label>" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // no label
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
            ]),
            $this->escape('no')
        );
        $expect = "<input type=\"$type\" value=\"yes\" />" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // label with "for"
        $actual = $input(
            $this->escape([
                'id' => 'input-yes',
                'type' => $type,
                'value' => 'yes',
                'label' => 'This is yes'
            ]),
            $this->escape('no')
        );
        $expect = "<label for=\"input-yes\"><input id=\"input-yes\" type=\"$type\" value=\"yes\" /> This is yes</label>" . PHP_EOL;
        $this->assertSame($expect, $actual);
        
        // unchecked
        
    }
    
    /**
     * @dataProvider provideGenericTypes
     */
    public function testGenericTypes($type)
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
        
        $expect = "<input type=\"$type\" value=\"button value\" />" . PHP_EOL;
        
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

    public function provideGenericTypes()
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

