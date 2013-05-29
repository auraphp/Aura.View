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
 * Helper for `<ul>` tag with `<li>` items.
 * 
 * @package Aura.Html
 * 
 */
abstract class AbstractList extends AbstractHelper
{
    /**
     * 
     * Attributes for the ul tag.
     * 
     * @var array
     * 
     */
    protected $attribs = [];
    
    /**
     * 
     * The stack of HTML elements.
     * 
     * @var array
     * 
     */
    protected $stack = [];
    
    /**
     * 
     * The generated HTML.
     * 
     * @var string
     * 
     */
    protected $html = '';
    
    /**
     * 
     * Initializes and returns the UL object.
     * 
     * @param array $attribs Attributes for the UL tag.
     * 
     * @return self
     * 
     * @todo As with select, allow a second param for the items?
     * 
     */
    public function __invoke(array $attribs = [])
    {
        $this->attribs = $attribs;
        $this->stack   = [];
        $this->html    = '';
        return $this;
    }
    
    /**
     * 
     * Adds a single item to the stack.
     * 
     * @param string $html The HTML for the list item text.
     * 
     * @param array $attribs Attributes for the list item tag.
     * 
     * @return self
     * 
     */
    public function item($html, array $attribs = [])
    {
        $this->stack[] = [$html, $attribs];
        return $this;
    }
    
    /**
     * 
     * Adds multiple items to the stack.
     * 
     * @param array $items An array of HTML for the list items.
     * 
     * @param array $attribs Attributes for each list item tag.
     * 
     * @return self
     * 
     */
    public function items($items, array $attribs = [])
    {
        foreach ($items as $html) {
            $this->item($html, $attribs);
        }
        return $this;
    }
    
    /**
     * 
     * Generates and returns the HTML for the list.
     * 
     * @return string
     * 
     */
    public function exec()
    {
        // if there is no stack of items, **do not** return an empty
        // <ul></ul> tag set.
        if (! $this->stack) {
            return;
        }
        
        $tag = $this->getTag();
        $attribs = $this->strAttribs($this->attribs);
        if ($attribs) {
            $this->html = $this->indent(0, "<{$tag} {$attribs}>");
        } else {
            $this->html = $this->indent(0, "<{$tag}>");
        }
        
        foreach ($this->stack as $item) {
            $this->buildItem($item);
        }
        
        $this->html .= $this->indent(0, "</{$tag}>");
        return $this->html;
    }
    
    /**
     * 
     * Builds the HTML for a single list item.
     * 
     * @param string $item The item HTML.
     * 
     * @return void
     * 
     */
    protected function buildItem($item)
    {
        list($html, $attribs) = $item;
        $attribs = $this->strAttribs($attribs);
        if ($attribs) {
            $this->html .= $this->indent(1, "<li {$attribs}>$html</li>");
        } else {
            $this->html .= $this->indent(1, "<li>$html</li>");
        }
    }
    
    /**
     * 
     * Returns the tag name.
     * 
     * @return string
     * 
     */
    abstract protected function getTag();
}
