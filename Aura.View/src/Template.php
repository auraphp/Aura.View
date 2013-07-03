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
     * @param string $name The rendering instructions; found via the Finder.
     * 
     * @return string
     * 
     */
    public function render($name)
    {
        // find the rendering code
        $render = $this->getFinder()->find($name);
        if (! $render) {
            throw new Exception\TemplateNotFound($name);
        }
        
        // if not already a closure, make one that requires a script file
        if (! $render instanceof Closure) {
            $file = $render;
            $render = function () use ($file) {
                require $file;
            };
        }
	    
        // bind the rendering code to this template object
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
}
