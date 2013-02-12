<?php
namespace Aura\View\Helper;

abstract class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $escape;
    
    protected function setUp()
    {
        parent::setUp();
        $this->escape = new Escape;
    }
    
    protected function escape($value)
    {
        $escape = $this->escape;
        return $escape($value);
    }
}
