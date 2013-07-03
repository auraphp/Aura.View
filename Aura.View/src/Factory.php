<?php
namespace Aura\View;

use Closure;

class Factory
{
    protected $finder;
    
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }
    
    public function getFinder()
    {
        return $this->finder;
    }
    
    public function newInstance($spec, $helper, $data)
    {
        // if the spec is already a closure, bind to a template and return
        if ($spec instanceof Closure) {
            return $this->bindClosure($spec, $helper, $data);
        }
        
        // can we find the specified template?
        $template = $this->finder->find($spec);
        if (! $template) {
            return false;
        }
        
        // if the result is a closure, bind to a "real" template and return
        if ($template instanceof Closure) {
            return $this->bindClosure($template, $helper, $data);
        }
        
        // create a new closure, bind to a template object, and return
        $closure = function () use ($template) {
            require $template;
        }
        return $this->bindClosure($closure, $helper, $data);
    }
    
    // binds the closure to a blank template. this means the closure
    // acts as the __invoke() method for the blank template.
    protected function bindClosure($closure, $helper, $data)
    {
        $newthis = new Template($this, $helper, $data);
        $closure = $closure->bindTo($newthis, get_class($newthis));
        return $closure;
    }
}
