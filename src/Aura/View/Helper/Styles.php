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
 * @package Aura.View
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
    protected $styles = [];

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
     * @param int $pos The stylesheet position in the stack.
     * 
     * @return void
     * 
     */
    public function add($href, $attribs = [], $pos = 100)
    {
        $base = [
            'rel'   => 'stylesheet',
            'href'  => $href,
            'type'  => 'text/css',
        ];

        if (! isset($attribs['media'])) {
            $base['media'] = 'screen';
        } else {
            $base['media'] = $attribs['media'];
        }

        unset($attribs['rel']);
        unset($attribs['href']);
        unset($attribs['type']);
        unset($attribs['media']);

        $attribs = array_merge($base, (array) $attribs);
        $tag = $this->void('link', $attribs);
        $this->styles[$tag] = $pos;
    }

    /**
     * 
     * Adds a conditional `<!--[if ...]><link rel="stylesheet" ... /><![endif] -->` 
     * tag to the stack.
     * 
     * @param string $exp The conditional expression for the stylesheet.
     * 
     * @param string $href The source href for the stylesheet.
     * 
     * @param array $attribs Additional attributes for the <link> tag.
     * 
     * @param string $pos The stylesheet position in the stack.
     * 
     * @return void
     * 
     */
    public function addCond($exp, $href, $attribs = [], $pos = 100)
    {
        $base = [
            'rel'   => 'stylesheet',
            'href'  => $href,
            'type'  => 'text/css',
        ];

        if (! isset($attribs['media'])) {
            $base['media'] = 'screen';
        } else {
            $base['media'] = $attribs['media'];
        }

        unset($attribs['rel']);
        unset($attribs['href']);
        unset($attribs['type']);
        unset($attribs['media']);

        $attribs = array_merge($base, (array) $attribs);
        $tag = "<!--[if $exp]>"
             . $this->void('link', $attribs)
             . "<![endif]-->";
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
