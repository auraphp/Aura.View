<?php
namespace Aura\Html\Helper;

use Aura\Html\Escape;

abstract class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;
    
    protected function setUp()
    {
        parent::setUp();
        $this->helper = $this->newHelper();
    }
    
    protected function newHelper()
    {
        $escape = new Escape(
            new Escape\AttrStrategy,
            new Escape\CssStrategy,
            new Escape\HtmlStrategy,
            new Escape\JsStrategy
        );
        
        $class = substr(get_class($this), 0, -4);
        $helper = new $class;
        $helper->setEscape($escape);
        return $helper;
    }
}