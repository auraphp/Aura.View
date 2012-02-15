<?php
namespace Aura\View\Escaper;
class IteratorAggregate extends Object implements \IteratorAggregate
{
    public function getIterator()
    {
        return $this->__escape($this->object->getIterator());
    }
}
