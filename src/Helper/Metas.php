<?php
namespace Aura\View\Helper;
use Aura\View\Helper;

/**
 * 
 * Helper for a stack of <meta ... /> tags.
 * 
 */
class Metas extends AbstractHelper
{
    protected $metas = array();
    
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
     * Returns a <meta ... /> tag.
     * 
     * @param array $attribs Attributes for the <link> tag.
     * 
     * @param int $pos The postion of meta tag in stack
     * 
     * @return void
     * 
     */
    public function add(array $attribs = array(), $pos = 100)
    {
        $attr = $this->attribs($attribs);
		$tag = "<meta $attr />";
        $this->metas[$tag] = $pos;
    }
    
    /**
     * 
     * Returns a <meta http-equiv="" content="" /> tag.
     * 
     * @param string $http_equiv The http-equiv type.
     * 
     * @param string $content The content value.
     * 
     * @param int $pos The postion of meta tag in stack
     * 
     * @return void
     * 
     */
    public function addHttp($http_equiv, $content, $pos = 100)
    {
        $this->add(array(
            'http-equiv' => $http_equiv,
            'content'    => $content,
        ), $pos);
    }
    
    /**
     * 
     * Returns a <meta name="" content="" /> tag.
     * 
     * @param string $name The name value.
     * 
     * @param string $content The content value.
     * 
     * @param int $pos The postion of meta tag in stack
     * 
     * @return void
     * 
     */
    public function addName($name, $content, $pos = 100)
    {
        $this->add(array(
            'name'    => $name,
            'content' => $content,
        ), $pos);
    }
    
    /**
     * 
     * Returns the stack of <meta ... /> tags as a single block.
     * 
     * @return string The <meta ... /> tags.
     * 
     */
    public function get()
    {
    	asort($this->metas);
        $metas = array_keys($this->metas);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $metas)
             . PHP_EOL;
    }
}
