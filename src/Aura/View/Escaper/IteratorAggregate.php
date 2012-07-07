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
namespace Aura\View\Escaper;

/**
 * 
 * IteratorAggregate
 * 
 * @package Aura.View
 * 
 */
class IteratorAggregate extends Object implements \IteratorAggregate
{
    // FIXME
    /**
     *
     * Iterator
     * 
     * @return type 
     * 
     */
    public function getIterator()
    {
        return $this->__escape($this->object->getIterator());
    }
}
