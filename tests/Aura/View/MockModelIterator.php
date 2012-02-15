<?php
namespace Aura\View;
class MockModelIterator implements \Iterator
{
    protected $data = [];
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
    
    public function __get($key)
    {
        return $this->data[$key];
    }
    
    public function current()
    {
        return current($this->data);
    }
    
    public function key()
    {
        return key($this->data);
    }
    
    public function next()
    {
        return next($this->data);
    }
    
    public function rewind()
    {
        return reset($this->data);
    }
    
    public function valid()
    {
        return key($this->data) !== null;
    }
    
    public function getThroughCall()
    {
        return __METHOD__;
    }
}
