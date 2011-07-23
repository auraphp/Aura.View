<?php
namespace Aura\View\Helper;
use Aura\View\Helper;

/**
 * 
 * Helper for a stack of <script> tags.
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
     * Adds a <script> tag to the stack.
     * 
     * @param string $src The source href for the script.
     * 
     * @param array $attribs Additional attributes for the <script> tag.
     * 
     * @return void
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
        $tag = "<script src=\"$src\" $attr></script>";
        $this->scripts[$tag] = $pos;
    }
    
	/**
     * 
     * Adds a <script> tag to the stack.
     * 
     * @param string $code The code for the script.
     * 
     * @param string $pos The position in the array for the script.
     * 
     * @param array $attribs Additional attributes for the <script> tag.
     * 
     * @return void
     * 
     */
    public function addInline($code, $pos = 100, array $attribs = array())
    {
        //unset($attribs['code']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }
                
        $attr = $this->attribs($attribs);
        $tag = "<script " . $attr . ">\n"
        	. $this->indent . trim($code) . "\n"
        	. $this->indent . "</script>";
        $this->scripts[$tag] = $pos;
    }
    
    /**
     * 
     * Adds a conditional <script> tag to the stack.
     * 
     * @param string $src The source href for the script.
     * 
     * @param string $con The conditional for the script.
     * 
     * @param string $pos The position in the array for the script.
     * 
     * @param array $attribs Additional attributes for the <script> tag.
     * 
     * @return void
     * 
     */
    public function addCond($src, $con, $pos = 100, array $attribs = array())
    {
        $src = $this->escape($src);
        unset($attribs['src']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }
                
        $attr = $this->attribs($attribs);
        $tag = "<!--[if $con]><script src=\"$src\" $attr></script><![endif]-->";
        $this->scripts[$tag] = $pos;
    }
    
    /**
     * 
     * Returns the stack of <script> tags as a single block.
     * 
     * @return string The <script> tags.
     * 
     */
    public function get()
    {
        asort($this->scripts);
        $scripts = array_keys($this->scripts);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $scripts)
             . PHP_EOL;
    }
}
