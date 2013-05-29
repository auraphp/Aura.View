<?php
namespace Aura\View\Helper;
use Aura\View\EscaperFactory;
abstract class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $escaper_object;
    
    protected function setUp()
    {
        $escaper_factory = new EscaperFactory;
        $this->escaper_object = $escaper_factory->newInstance((object) []);
    }
    
    protected function escape($val)
    {
        return $this->escaper_object->__escape($val);
    }
}
