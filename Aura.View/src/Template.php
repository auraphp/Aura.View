<?php
namespace Aura\View;

use Closure;

/**
 * 
 * Concrete template object; having render() and partial() here keeps script
 * code away from the private properties of the AbstractTemplate.
 * 
 */
class Template extends AbstractTemplate
{
    /**
     * 
     * Returns rendered output.
     * 
     * @param string $spec The rendering instructions; found via the Finder.
     * 
     * @return string
     * 
     */
    public function render($name)
    {
        // convert the spec to a closure for rendering
        $render = $this->convertToClosure($name);
        
        // bind the rendering closure to this template object
        $render = $render->bindTo($this, get_class($this));

        // render and return
        ob_start();
        $render();
        return ob_get_clean();
    }
    
    public function partial($name, $data)
    {
        $partial = clone $this;
        $partial->setData($data);
        return $partial->render($name);
    }

    protected function convertToClosure($spec)
    {
        // is it already a closure?
        if ($spec instanceof Closure) {
            return $spec;
        }
        
        // look for it in the finder
        $found = $this->getFinder()->find($spec);
        
        // did we find anything?
        if (! $found) {
            throw new Exception\TemplateNotFound($spec);
        }
        
        // did we find a closure?
        if ($found instanceof Closure) {
            return $found;
        }
        
        // no, we found a file name; convert to closure
        $file = $found;
        return function () use ($file) {
            require $file;
        };
    }
}
