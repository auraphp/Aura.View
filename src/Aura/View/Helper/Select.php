<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View\Helper;

/**
 * 
 * Helper for `<select>` tag.
 * 
 * @package Aura.View
 * 
 */
class Select extends AbstractHelper
{
    protected $stack = [];
    
    protected $attribs = [];
    
    protected $optgroup = false;
    
    protected $selected = [];
    
    protected $html = '';
    
    /**
     * 
     * Building simple <select> tag to complex ones
     * 
     * $this->select(
     *      [
     *          'name' => 'foo[bar]',
     *          'multiple' => 'multiple'
     *      ],
     *      [
     *          'value1' => 'First Label',
     *          'value2' => 'Second Label',
     *          'value5' => 'Fifth Label',
     *          'value3' => 'Third Label',
     *      ],
     *      'value5'
     *  );
     * 
     * 
     * 
     * $this->select(['name' => 'foo[bar]', 'multiple' => 'multiple'])
     *     ->optgroup('Group A')
     *     ->options(
     *         [
     *             'value1' => 'First Label',
     *             'value2' => 'Second Label',
     *         ]
     *     )
     *     ->optgroup('Group B')
     *     ->options(
     *         [
     *             'value5' => 'Fifth Label',
     *             'value3' => 'Third Label',
     *          ]
     *      )
     *      ->option(
     *          'counting',
     *          'Three sir!',
     *          ['disabled' => 'disabled']
     *      )
     *      ->selected(['value2', 'value3'])
     *      ->fetch();
     * 
     * @param array $attribs
     * 
     * @param array $options
     * 
     * @param array $selected
     * 
     * @return Aura\View\Helper\Select|string
     * 
     */
    public function __invoke($attribs, $options = [], $selected = null)
    {
        $this->stack    = [];
        $this->optgroup = false;
        $this->selected = [];
        $this->html     = '';
        $this->attribs  = $attribs;
        
        if ($options) {
            $this->options($options);
            $this->selected($selected);
            return $this->fetch();
        } else {
            return $this;
        }
    }
    
    public function option($value, $label, array $attribs = [])
    {
        $this->stack[] = ['buildOption', $value, $label, $attribs];
        return $this;
    }
    
    public function options(array $options, array $attribs = [])
    {
        foreach ($options as $value => $label) {
            $this->option($value, $label, $attribs);
        }
        return $this;
    }
    
    public function optgroup($label, array $attribs = [])
    {
        if ($this->optgroup) {
            $this->stack[] = ['endOptgroup'];
        }
        $this->stack[] = ['beginOptgroup', $label, $attribs];
        $this->optgroup = true;
        return $this;
    }
    
    public function selected($selected)
    {
        $this->selected = (array) $selected;
        return $this;
    }
    
    public function fetch()
    {
        $attr = $this->attribs($this->attribs);
        $this->html = "<select {$attr}>" . PHP_EOL;
        
        foreach ($this->stack as $info) {
            $method = array_shift($info);
            $this->$method($info);
        }
        
        if ($this->optgroup) {
            $this->endOptgroup();
        }
        
        $this->html .= '</select>';
        return $this->html;
    }
    
    protected function buildOption($info)
    {
        list($value, $label, $attribs) = $info;
        
        // set the option value into the attribs
        $attribs['value'] = $value;
        
        // is the value selected?
        unset($attribs['selected']);
        if (in_array($value, $this->selected)) {
            $attribs['selected'] = 'selected';
        }
        
        // build attributes and return option tag with label text
        $attr = $this->attribs($attribs);
        $this->html .= "    <option {$attr}>$label</option>" . PHP_EOL;
    }
    
    protected function beginOptgroup($info)
    {
        list($label, $attribs) = $info;
        $attribs['label'] = $label;
        $attr = $this->attribs($attribs);
        $this->html .= "  <optgroup {$attr}>" . PHP_EOL;
    }
    
    protected function endOptgroup()
    {
        $this->html .= "  </optgroup>" . PHP_EOL;
    }
}
