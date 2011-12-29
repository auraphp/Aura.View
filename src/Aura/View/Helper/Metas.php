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
 * Helper for a stack of <meta ... /> tags.
 * 
 */
class Metas extends AbstractHelper
{
    /**
     * 
     * The array of all metas added to the helper.
     * 
     * @var array
     * 
     */
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
     * @return void
     * 
     */
    public function add(array $attribs = array())
    {
        $attr = $this->attribs($attribs);
        $this->metas[] = "<meta $attr />";
    }
    
    /**
     * 
     * Returns a <meta http-equiv="" content="" /> tag.
     * 
     * @param string $http_equiv The http-equiv type.
     * 
     * @param string $content The content value.
     * 
     * @return void
     * 
     */
    public function addHttp($http_equiv, $content)
    {
        $this->add(array(
            'http-equiv' => $http_equiv,
            'content'    => $content,
        ));
    }
    
    /**
     * 
     * Returns a <meta name="" content="" /> tag.
     * 
     * @param string $name The name value.
     * 
     * @param string $content The content value.
     * 
     * @return void
     * 
     */
    public function addName($name, $content)
    {
        $this->add(array(
            'name'    => $name,
            'content' => $content,
        ));
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
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $this->metas)
             . PHP_EOL;
    }
}
