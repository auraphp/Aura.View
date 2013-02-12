<?php
namespace Aura\View\Helper;

abstract class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    protected function escape($value)
    {
        // don't escape right now
        return $value;
    }
}
