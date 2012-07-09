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
 * Returns an iterator wrapped in an escaper.
 * 
 * @package Aura.View
 * 
 */
class IteratorAggregate extends Object implements \IteratorAggregate
{
    /**
     * 
     * Returns an iterator wrapped in an escaper.
     * 
     * @return Iterator
     * 
     */
    public function getIterator()
    {
        return $this->__escape($this->object->getIterator());
    }
}
