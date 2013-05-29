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
 * An iterator for escaper objects.
 * 
 * @package Aura.View
 * 
 */
class Iterator extends Object implements \Iterator
{
    /**
     * 
     * Returns the current value.
     * 
     * @return mixed
     * 
     */
    public function current()
    {
        return $this->__escape($this->object->current());
    }

    /**
     * 
     * Returns the current key.
     * 
     * @return mixed
     * 
     */
    public function key()
    {
        return $this->__escape($this->object->key());
    }

    /**
     * 
     * Returns the next item.
     * 
     * @return mixed
     * 
     */
    public function next()
    {
        return $this->object->next();
    }

    /**
     * 
     * Rewinds the iterator the start.
     * 
     * @return mixed
     * 
     */
    public function rewind()
    {
        return $this->object->rewind();
    }

    /**
     * 
     * Is the current position valid?
     * 
     * @return bool
     * 
     */
    public function valid()
    {
        return $this->object->valid();
    }
}
