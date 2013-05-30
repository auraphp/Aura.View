<?php
namespace Aura\Html;

use Aura\Html\Escape\AttrStrategy;
use Aura\Html\Escape\CssStrategy;
use Aura\Html\Escape\HtmlStrategy;
use Aura\Html\Escape\JsStrategy;

class Escape
{
    public function __construct(
        AttrStrategy $attr,
        CssStrategy  $css,
        HtmlStrategy $html,
        JsStrategy   $js
    ) {
        $this->attr = $attr;
        $this->css  = $css;
        $this->html = $html;
        $this->js   = $js;
    }
    
    public function html($raw)
    {
        return $raw;
    }
    
    public function attr($raw)
    {
        return $raw;
    }
    
    public function js($raw)
    {
        return $raw;
    }
    
    public function css($raw)
    {
        return $raw;
    }
}
