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
 * Helper for <link rel="stylesheet" ... /> tags.
 * 
 */
class Styles extends AbstractHelper
{
    /**
     * 
     * The array of all styles added to the helper.
     * 
     * @var array
     * 
     */
    protected $styles = array();
    
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
     * Adds a <link rel="stylesheet" ... /> tag to the stack.
     * 
     * @param string $href The source href for the stylesheet.
     * 
     * @param array $attribs Additional attributes for the <link> tag.
     * 
     * @return void
     * 
     */
    public function add($href, $pos = 100, array $attribs = array())
    {
        if ($pos === null) {
            $pos = 100;
        }
        
        $base = array(
            'rel'   => 'stylesheet',
            'href'  => $href,
            'type'  => 'text/css',
        );
        
        if (! isset($attribs['media'])) {
            $base['media'] = 'screen';
        } else {
            $base['media'] = $attribs['media'];
        }
        
        unset($attribs['rel']);
        unset($attribs['href']);
        unset($attribs['type']);
        unset($attribs['media']);
        
        $attr = $this->attribs($base + $attribs);
        $tag = "<link $attr />";
        $this->styles[$tag] = $pos;
    }
    
    /**
     * 
     * Returns the stack of <link rel="stylesheet" ... /> tags as a single 
     * block.
     * 
     * @return string The <link rel="stylesheet" ... /> tags.
     * 
     */
    public function get()
    {
        asort($this->styles);
        $styles = array_keys($this->styles);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $styles)
             . PHP_EOL;
    }
}
