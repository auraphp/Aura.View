<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for <link rel="stylesheet" ... /> tags.
 * 
 */
class Styles extends AbstractHelper
{
    protected $styles = array();
    
    /**
     * 
     * Returns a <link rel="stylesheet" ... /> tag.
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
        unset($attribs['type']);
        unset($attribs['href']);
        unset($attribs['media']);
        
        $attr = $this->attribs($base + $attribs);
        $tag = "<link$attr />";
        $this->styles[$tag] = $pos;
    }
    
    public function get()
    {
        asort($this->styles);
        $styles = array_keys($this->styles);
        return $this->indent 
             . implode(PHP_EOL . $this->indent, $styles)
             . PHP_EOL;
    }
}
