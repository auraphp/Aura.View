<?php
namespace Aura\View\Escaper;
class Iterator extends Object implements \Iterator
{
    public function current()
    {
        return $this->__escape($this->object->current());
    }
    
    public function key()
    {
        return $this->__escape($this->object->key());
    }
    
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
