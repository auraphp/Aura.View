<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Helper;

/**
 * 
 * Helper for a stack of <script> tags.
 * 
 * @package Aura.Html
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
     * @param array $attr Additional attributes for the <script> tag.
     * 
     * @param int $pos The script position in the stack.
     * 
     * @return void
     * 
     */
    public function add($src, array $attr = [], $pos = 100)
    {
        $base = [
            'src' => $src,
            'type' => 'text/javascript',
        ];
        
        unset($attr['src']);
        
        $attr = $this->escaper->attr(array_merge($base, $attr));
        $tag = "<script $attr></script>";
        $this->scripts[(int) $pos][] = $tag;
    }

    /**
     * 
     * Adds a conditional `<!--[if ...]><script><![endif] -->` tag to the 
     * stack.
     * 
     * @param string $cond The conditional expression for the script.
     * 
     * @param string $src The source href for the script.
     * 
     * @param array $attr Additional attributes for the <script> tag.
     * 
     * @param string $pos The script position in the stack.
     * 
     * @return void
     * 
     */
    public function addCond($cond, $src, array $attr = [], $pos = 100)
    {
        $base = [
            'src' => $src,
            'type' => 'text/javascript',
        ];
        
        unset($attr['src']);
        
        $attr = $this->escaper->attr(array_merge($base, $attr));
        $cond = $this->escaper->html($cond);
        
        $tag = "<!--[if $cond]><script $attr></script><![endif]-->";
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
