<?php
namespace Aura\View;

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
    
    public function newInstance($name, $helper, $data)
    {
        // can we find the named template?
        $template = $this->finder->find($name);
        if (! $template) {
            throw new Exception\TemplateNotFound($name);
        }
        
        if ($template instanceof \Closure) {
            // bind the closure to a blank template. this means the closure
            // acts as the __invoke() method for the blank template.
            $newthis  = new Template($this, $helper, $data);
            $template = $template->bindTo($newthis, get_class($newthis));
            return $template;
        }
        
        // create a new object using the result as the class name
        $template = new $template($this, $helper, $data);
        return $template;
    }
}
