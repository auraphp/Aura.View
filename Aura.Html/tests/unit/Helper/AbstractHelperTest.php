<?php
namespace Aura\Html\Helper;

use Aura\Html\Escaper;

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
        $class = substr(get_class($this), 0, -4);
        $helper = new $class;
        $helper->setEscaper(new Escaper);
        return $helper;
    }
}
