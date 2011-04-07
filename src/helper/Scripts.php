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
    protected $scripts_head = array();
    
    protected $scripts_foot = array();
    
    /**
     * 
     * Returns a <script></script> tag.
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
        $tag = $this->tag($src, $attribs);
        $this->scripts_head[$tag] = $pos;
    }
    
    public function addFoot($src, $pos = 100, array $attribs = array())
    {
        $tag = $this->tag($src, $attribs);
        $this->scripts_foot[$tag] = $pos;
    }
    
    public function get()
    {
        asort($this->scripts_head);
        $scripts = array_keys($this->scripts_head);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $scripts)
             . PHP_EOL;
    }
    
    public function getFoot()
    {
        asort($this->scripts_foot);
        $scripts = array_keys($this->scripts_foot);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $scripts)
             . PHP_EOL;
    }
    
    protected function tag($src, array $attribs = array())
    {
        $src = $this->escape($src);
        unset($attribs['src']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }
        
        $attr = $this->attribs($attribs);
        return "<script src=\"$src\"$attr></script>";
    }
}
