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
 * Helper for a stack of <link ... /> tags.
 * 
 * @package Aura.Html
 * 
 */
class Links extends AbstractHelper
{
    /**
     * 
     * The array of all links added to the helper.
     * 
     * @var array
     * 
     */
    protected $links = [];

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
     * Adda a <link ... > tag to the stack.
     * 
     * @param array $attr Attributes for the <link> tag.
     * 
     * @return void
     * 
     */
    public function add(array $attr = [])
    {
        $this->links[] = $this->void('link', $attr);
    }

    /**
     * 
     * Returns the stack of <link ... /> tags as a single block.
     * 
     * @return string The <link ... /> tags.
     * 
     */
    public function get()
    {
        return $this->indent(1, implode(PHP_EOL . $this->indent, $this->links));
    }
}
