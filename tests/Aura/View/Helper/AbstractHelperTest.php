<?php
namespace Aura\View\Helper;
use Aura\View\EscaperFactory;
abstract class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $escaper_factory;
    
    protected function setUp()
    {
        $this->escaper_factory = new EscaperFactory;
    }
    
}