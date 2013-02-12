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
 * Helper for `<ul>` tag with `<li>` items.
 * 
 * @package Aura.View
 * 
 */
class Ul extends AbstractHelper
{
    protected $attr = [];
    
    protected $stack = [];
    
    protected $html = '';
    
    public function __invoke(array $attr = [])
    {
        $this->attr = $attr;
        $this->stack   = [];
        $this->html    = '';
        return $this;
    }
    
    public function getTag()
    {
        return 'ul';
    }
    
    public function item($html, array $attr = [])
    {
        $this->stack[] = [$html, $attr];
        return $this;
    }
    
    public function items(array $items, array $attr = [])
    {
        foreach ($items as $html) {
            $this->item($html, $attr);
        }
        return $this;
    }
    
    public function get()
    {
        // if there is no stack of items, **do not** return an empty
        // <ul></ul> tag set.
        if (! $this->stack) {
            return;
        }
        
        $tag = $this->getTag();
        $attr = $this->attr($this->attr);
        if ($attr) {
            $this->html = $this->indent(0, "<{$tag} {$attr}>");
        } else {
            $this->html = $this->indent(0, "<{$tag}>");
        }
        
        foreach ($this->stack as $item) {
            $this->buildItem($item);
        }
        
        $this->html .= $this->indent(0, "</{$tag}>");
        return $this->html;
    }
    
    protected function buildItem($item)
    {
        list($html, $attr) = $item;
        $attr = $this->attr($attr);
        if ($attr) {
            $this->html .= $this->indent(1, "<li {$attr}>$html</li>");
        } else {
            $this->html .= $this->indent(1, "<li>$html</li>");
        }
    }
}
