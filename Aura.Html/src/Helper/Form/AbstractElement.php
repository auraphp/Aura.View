<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Helper\Form;

use Aura\Html\Helper\AbstractHelper;
use Aura\Html\Exception;

/**
 * 
 * Abstact helper for elements that can be checked (e.g. radio or checkbox).
 * 
 * @package Aura.Html
 * 
 */
abstract class AbstractElement extends AbstractHelper
{
    /**
     * 
     * The element type.
     * 
     * @var string
     * 
     */
    protected $type;
    
    /**
     * 
     * The element name.
     * 
     * @var string
     * 
     */
    protected $name;
    
    /**
     * 
     * The current value of the element.
     * 
     * @var mixed
     * 
     */
    protected $value;
    
    /**
     * 
     * HTML attributes for the element.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * Value options for the element.
     * 
     * @var array
     * 
     */
    protected $options = [];
    
    /**
     * 
     * Given a element spec, returns the HTML for the element.
     * 
     * @param array $spec The element spec.
     * 
     * @return string
     * 
     */
    abstract public function __invoke(array $spec = [])
    {
        // base spec elements
        $base = [
            'type' => 'text',
            'name' => null,
            'value' => null,
            'attribs' => [],
            'options' => [],
        ];
        
        // make sure we have the base spec elements
        $spec = array_merge($base, $spec);
        
        // retain as properties
        $this->type    = $spec['type'];
        $this->name    = $spec['name'];
        $this->value   = $spec['value'];
        $this->attribs = $spec['attribs'];
        $this->options = $spec['options'];
        
        // is there a type?
        if (! $this->type) {
            throw new Exception("No type.");
        }
        
        // is there a name?
        if (! $this->name) {
            throw new Exception("No name.");
        }
        
        // generate the html
        return $this->html();
    }
    
    /**
     * 
     * Returns the HTML for this element.
     * 
     * @return string
     * 
     */
    abstract protected function html();
}
