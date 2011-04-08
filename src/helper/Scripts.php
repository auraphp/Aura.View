<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for <script> tags from a public Solar resource.
 * 
 */
class Scripts extends AbstractHelper
{
    protected $scripts = array();
    
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
     * Adds a script.
     * 
     * @param string $src The source href for the script.
     * 
     * @param array $attribs Additional attributes for the <script> tag.
     * 
     * @return string The <script></script> tag.
     * 
     */
    public function add($src, $pos = 100, array $attribs = array())
    {
        $src = $this->escape($src);
        unset($attribs['src']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }
        
        $attr = $this->attribs($attribs);
        $tag = "<script src=\"$src\"$attr></script>";
        $this->scripts[$tag] = $pos;
    }
    
    public function get()
    {
        asort($this->scripts);
        $scripts = array_keys($this->scripts);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $scripts)
             . PHP_EOL;
    }
}
