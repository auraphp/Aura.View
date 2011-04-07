<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for a generic <link ... /> tag.
 * 
 */
class Links extends AbstractHelper
{
    protected $links = array();
    
    /**
     * 
     * Returns a <link ... /> tag.
     * 
     * @param array $attribs Attributes for the <link> tag.
     * 
     * @return string The <link ... /> tag.
     * 
     */
    public function add(array $attribs = array())
    {
        $attr = $this->attribs($attribs);
        $this->links[] = "<link$attr />";
    }
    
    public function get()
    {
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $this->links)
             . PHP_EOL;
    }
}
