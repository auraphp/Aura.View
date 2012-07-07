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
 * Iterator
 * 
 * @package Aura.View
 * 
 */
class Iterator extends Object implements \Iterator
{
    // FIXME in all this
    /**
     *
     * Return the current element
     * 
     * @return mixed 
     */
    public function current()
    {
        return $this->__escape($this->object->current());
    }
    
    /**
     * 
     * Return the key of the current element
     *
     * @return scalar
     * 
     */
    public function key()
    {
        return $this->__escape($this->object->key());
    }
    
    /**
     * Next
     * 
     * @return 
     * 
     */
    public function next()
    {
        return $this->object->next();
    }
    
    public function rewind()
    {
        return $this->object->rewind();
    }
    
    public function valid()
    {
        return $this->object->valid();
    }
}
