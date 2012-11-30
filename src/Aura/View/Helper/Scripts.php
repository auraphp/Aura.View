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
 * Helper for a stack of <script> tags.
 * 
 * @package Aura.View
 * 
 */
class Scripts extends AbstractHelper
{
    /**
     * 
     * The array of all scripts added to the helper.
     * 
     * @var array
     * 
     */
    protected $scripts = [];

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
     * @param int $pos The script position in the stack.
     * 
     * @return void
     * 
     */
    public function add($src, $attribs = [], $pos = 100)
    {
        unset($attribs['src']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }

        $attr = $this->attribs($attribs);
        $tag = "<script src=\"$src\" $attr></script>";
        $this->scripts[(int) $pos][] = $tag;
    }

    /**
     * 
     * Adds a conditional `<!--[if ...]><script><![endif] -->` tag to the 
     * stack.
     * 
     * @param string $exp The conditional expression for the script.
     * 
     * @param string $src The source href for the script.
     * 
     * @param array $attribs Additional attributes for the <script> tag.
     * 
     * @param string $pos The script position in the stack.
     * 
     * @return void
     * 
     */
    public function addCond($exp, $src, $attribs = [], $pos = 100)
    {
        unset($attribs['src']);
        if (empty($attribs['type'])) {
            $attribs['type'] = 'text/javascript';
        }

        $attr = $this->attribs($attribs);
        $tag = "<!--[if $exp]><script src=\"$src\" $attr></script><![endif]-->";
        $this->scripts[(int) $pos][] = $tag;
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
        $html = '';
        ksort($this->scripts);
        foreach ($this->scripts as $list) {
            foreach ($list as $script) {
                $html .= $this->indent . $script . PHP_EOL;
            }
        }
        return $html;
    }
}
