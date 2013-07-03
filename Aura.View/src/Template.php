<?php
namespace Aura\View;

class Template extends AbstractTemplate
{
    /**
     * 
     * Renders a sub-template and returns its output, optionally with 
     * scope-separated data.
     * 
     * @param string $name The template to render to use.
     * 
     * @return string
     * 
     */
    public function render($name)
    {
        // get the template execution code
        $render = $this->finder->find($name);
        if (! $render) {
            throw new Exception\TemplateNotFound($name);
        }
        
        // if not already a closure, make one that requires a script file
        if (! $render instanceof Closure) {
            $file = $render;
            $render = function () use ($file) {
                require $file;
            }
        }
        
        // bind the template to this object
        $render = $render->bindTo($this, get_class($this));

        // render and return
        ob_start();
        $render();
        return ob_get_clean();
    }
    
    public function partial($name, array $data = [])
    {
        $partial = clone($this);
        $partial->setData((object) $data);
        return $partial->render($name);
    }
}
