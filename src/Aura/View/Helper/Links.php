<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View\Helper;

/**
 * 
 * Helper for a stack of <link ... /> tags.
 * 
 * @package Aura.View
 * 
 */
class Links extends AbstractHelper
{
    /**
     * 
     * The array of all links added to the helper.
     * 
     * @var array
     * 
     */
    protected $links = array();
    
    /**
     * 
     * Returns the helper so you can call methods on it.
     * 
     * @return $this
     * 
     */
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * 
     * Adda a <link ... /> tag to the stack.
     * 
     * @param array $attribs Attributes for the <link> tag.
     * 
     * @return void
     * 
     */
    public function add(array $attribs = array())
    {
        $attr = $this->attribs($attribs);
        $this->links[] = "<link $attr />";
    }
    
    /**
     * 
     * Returns the stack of <link ... /> tags as a single block.
     * 
     * @return string The <link ... /> tags.
     * 
     */
    public function get()
    {
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $this->links)
             . PHP_EOL;
    }
}
