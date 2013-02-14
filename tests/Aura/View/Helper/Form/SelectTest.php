<?php
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelperTest;

class SelectTest extends AbstractHelperTest
{
    public function testQuick()
    {
        $select = new Select;
        
        $actual = $select(
            $this->escape([
                'name' => 'foo[bar]',
                'placeholder' => 'Please pick one',
            ]),
            $this->escape([
                'value1' => 'First Label',
                'value2' => 'Second Label',
                'value5' => 'Fifth Label',
                'value3' => 'Third Label',
            ]),
            $this->escape('value5')
        );
        
        $expect = '<select name="foo[bar]">' . PHP_EOL
                . '    <option value="">Please pick one</option>' . PHP_EOL
                . '    <option value="value1">First Label</option>' . PHP_EOL
                . '    <option value="value2">Second Label</option>' . PHP_EOL
                . '    <option value="value5" selected="selected">Fifth Label</option>' . PHP_EOL
                . '    <option value="value3">Third Label</option>' . PHP_EOL
                . '</select>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
    }
    
    public function testDetailed()
    {
        $select = new Select;
        
        $actual = $select($this->escape([
                'name' => 'foo[bar]',
                'multiple' => 'multiple',
            ]))
            ->optgroup($this->escape('Group A'))
            ->options($this->escape([
               'value1' => 'First Label',
               'value2' => 'Second Label',
            ]))
            ->optgroup($this->escape('Group B'))
            ->options($this->escape([
               'value5' => 'Fifth Label',
               'value3' => 'Third Label',
            ]))
            ->option(
               $this->escape('counting'),
               $this->escape('Three sir!'),
               $this->escape(['disabled' => 'disabled'])
            )
            ->selected($this->escape(['value2', 'value3']))
            ->fetch();
        
        $expect = '<select name="foo[bar][]" multiple="multiple">' . PHP_EOL
                . '    <optgroup label="Group A">' . PHP_EOL
                . '        <option value="value1">First Label</option>' . PHP_EOL
                . '        <option value="value2" selected="selected">Second Label</option>' . PHP_EOL
                . '    </optgroup>' . PHP_EOL
                . '    <optgroup label="Group B">' . PHP_EOL
                . '        <option value="value5">Fifth Label</option>' . PHP_EOL
                . '        <option value="value3" selected="selected">Third Label</option>' . PHP_EOL
                . '        <option disabled="disabled" value="counting">Three sir!</option>' . PHP_EOL
                . '    </optgroup>' . PHP_EOL
                . '</select>' . PHP_EOL;
        
        $this->assertSame($expect, $actual);
    }
}
