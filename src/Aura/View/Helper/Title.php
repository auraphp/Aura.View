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
 * Sets the title of the layout
 *  
 * @package Aura.View
 * 
 */
class Title extends AbstractHelper
{
    /**
     * 
     * The <title> value.
     * 
     * @var string
     * 
     */
    protected $title = null;

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
     * Sets the <title> string.
     * 
     * @param string $title The title string.
     * 
     * @return void
     * 
     */
    public function set($title)
    {
        $this->title = $title;
    }

    /**
     * 
     * Appends to the end of the current <title> string.
     * 
     * @param string $text The string to be appended to the title.
     * 
     * @return void
     * 
     */
    public function append($text)
    {
        $this->title .= $text;
    }

    /**
     * 
     * Prepends to the beginning of the current <title> string.
     * 
     * @param string $text The string to be appended to the title.
     * 
     * @return void
     * 
     */
    public function prepend($text)
    {
        $this->title = $text . $this->title;
    }

    /**
     * 
     * Returns the current title string.
     * 
     * @return string The current title string.
     * 
     */
    public function get()
    {
        return $this->indent(1, "<title>{$this->title}</title>");
    }
}
