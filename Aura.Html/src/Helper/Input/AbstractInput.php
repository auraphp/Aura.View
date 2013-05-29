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
namespace Aura\Html\Helper\Input;

use Aura\Html\Helper\AbstractHelper;
use Aura\Html\Exception;

/**
 * 
 * Abstact helper for inputs that can be checked (e.g. radio or checkbox).
 * 
 * @package Aura.Html
 * 
 */
abstract class AbstractInput extends AbstractHelper
{
    /**
     * 
     * The input type.
     * 
     * @var string
     * 
     */
    protected $type;
    
    /**
     * 
     * The input name.
     * 
     * @var string
     * 
     */
    protected $name;
    
    /**
     * 
     * The current value of the input.
     * 
     * @var mixed
     * 
     */
    protected $value;
    
    /**
     * 
     * HTML attributes for the input.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * Value options for the input.
     * 
     * @var array
     * 
     */
    protected $options = [];
    
    /**
     * 
     * Given a input spec, returns the HTML for the input.
     * 
     * @param array $spec The input spec.
     * 
     * @return string
     * 
     */
    public function __invoke(array $spec = [])
    {
        $this->prep($spec);
        return $this->html();
    }
    
    protected function prep($spec)
    {
        // base spec inputs
        $base = [
            'type' => null,
            'name' => null,
            'value' => null,
            'attribs' => [],
            'options' => [],
        ];
        
        // make sure we have the base spec inputs
        $spec = array_merge($base, $spec);
        
        // retain as properties
        $this->type    = $spec['type'];
        $this->name    = $spec['name'];
        $this->value   = $spec['value'];
        $this->attribs = $spec['attribs'];
        $this->options = $spec['options'];
        
        // set up base attributes
        $attribs = [
            'id'   => null,
            'type' => null,
            'name' => $this->name,
        ];
        $this->attribs = array_merge($attribs, $this->attribs);
    }
    
    /**
     * 
     * Returns the HTML for this input.
     * 
     * @return string
     * 
     */
    abstract protected function html();
}
