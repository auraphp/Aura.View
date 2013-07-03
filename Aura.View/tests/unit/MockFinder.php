<?php
namespace Aura\View;

class MockFinder extends Finder
{
    public $is_readable = [];
    
    public function is_readable($file)
    {
        return in_array($this->is_readable, $file);
    }
}
