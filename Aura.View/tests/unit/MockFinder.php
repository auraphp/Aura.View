<?php
namespace Aura\View;

class MockFinder extends Finder
{
    public $is_readable = [];
    
    protected function isReadable($file)
    {
        // for coverage
        parent::isReadable(DIRECTORY_SEPARATOR);
        
        // for testing
        return in_array($file, $this->is_readable);
    }
    
    protected function getClosure($file)
    {
        // for coverage
        parent::getClosure($file);
        
        // for testing
        return function () use ($file) {
            return $file;
        };
    }
}
