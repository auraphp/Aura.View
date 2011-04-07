<?php
namespace aura\view\helper;
use aura\view\Helper;

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
     * Returns the current title string (escaped).
     * 
     * @return string The current title string.
     * 
     */
    public function get()
    {
        return $this->indent
             . "<title>"
             . $this->escape($this->title)
             . "</title>"
             . PHP_EOL;
    }
    
    /**
     * 
     * Returns the current title string **unescaped**.
     * 
     * @return void
     * 
     */
    public function getRaw()
    {
         return $this->indent
             . "<title>"
             . $this->title
             . "</title>"
             . PHP_EOL;
   }   
}
