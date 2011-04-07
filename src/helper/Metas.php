<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for a generic <link ... /> tag.
 * 
 */
class Metas extends AbstractHelper
{
    protected $metas = array();
    
    /**
     * 
     * Returns a <meta ... /> tag.
     * 
     * @param array $attribs Attributes for the <link> tag.
     * 
     * @return string The <meta ... /> tag.
     * 
     */
    public function add(array $attribs = array())
    {
        $attr = $this->attribs($attribs);
        $this->metas[] = "<meta$attr />";
    }
    
    /**
     * 
     * Returns a <meta http-equiv="" content="" /> tag.
     * 
     * @param string $http_equiv The http-equiv type.
     * 
     * @param string $content The content value.
     * 
     * @return string The <meta http-equiv="" content="" /> tag.
     * 
     */
    public function addHttp($http_equiv, $content)
    {
        $this->add(array(
            'http-equiv' => $http_equiv,
            'content' => $content,
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
     * @return string The <meta name="" content="" /> tag.
     * 
     */
    public function addName($name, $content)
    {
        $this->add(array(
            'name' => $name,
            'content' => $content,
        ));
    }
    
    public function get()
    {
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $this->metas)
             . PHP_EOL;
    }
}
