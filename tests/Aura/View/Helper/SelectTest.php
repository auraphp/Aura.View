<?php
namespace Aura\View\Helper;

class SelectTest extends AbstractHelperTest
{
    public function testQuick()
    {
        $select = new Select;
        
        $actual = $select(
            [
                'name' => 'foo[bar]',
            ],
            [
                'value1' => 'First Label',
                'value2' => 'Second Label',
                'value5' => 'Fifth Label',
                'value3' => 'Third Label',
            ],
            'value5'
        );
        
        $expect = '<select name="foo[bar]">' . PHP_EOL
                . '    <option value="value1">First Label</option>' . PHP_EOL
                . '    <option value="value2">Second Label</option>' . PHP_EOL
                . '    <option value="value5" selected="selected">Fifth Label</option>' . PHP_EOL
                . '    <option value="value3">Third Label</option>' . PHP_EOL
                . '</select>';
        
        $this->assertSame($expect, $actual);
    }
    
    public function testDetailed()
    {
        $select = new Select;
        
        $actual = $select([
                'name' => 'foo[bar]',
                'multiple' => 'multiple',
            ])
            ->optgroup('Group A')
            ->options([
               'value1' => 'First Label',
               'value2' => 'Second Label',
            ])
            ->optgroup('Group B')
            ->options([
               'value5' => 'Fifth Label',
               'value3' => 'Third Label',
            ])
            ->option(
               'counting',
               'Three sir!',
               ['disabled' => 'disabled']
            )
            ->selected(['value2', 'value3'])
            ->fetch();
        
        $expect = '<select name="foo[bar]" multiple="multiple">' . PHP_EOL
                . '  <optgroup label="Group A">' . PHP_EOL
                . '    <option value="value1">First Label</option>' . PHP_EOL
                . '    <option value="value2" selected="selected">Second Label</option>' . PHP_EOL
                . '  </optgroup>' . PHP_EOL
                . '  <optgroup label="Group B">' . PHP_EOL
                . '    <option value="value5">Fifth Label</option>' . PHP_EOL
                . '    <option value="value3" selected="selected">Third Label</option>' . PHP_EOL
                . '    <option disabled="disabled" value="counting">Three sir!</option>' . PHP_EOL
                . '  </optgroup>' . PHP_EOL
                . '</select>';
        
        $this->assertSame($expect, $actual);
    }
}
