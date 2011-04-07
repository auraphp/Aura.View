<?php
namespace aura\view;

/**
 * 
 * Implement fetch() separately from the base, so that template files do not
 * have access to TemplateBase private properties.
 * 
 */
class Template extends AbstractTemplate
{
    /**
     * 
     * Fetches template output; doubles as a partial template method.
     * 
     * @param string $name The template to process.
     * 
     * @param string $vars Variables to extract into the current scope.
     * 
     * @return string The template output.
     * 
     */
    public function fetch($name, array $vars = array())
    {
        unset($name);
        if ($vars) {
            unset($vars);
            extract(func_get_arg(1), EXTR_SKIP);
        }
        ob_start();
        require $this->find(func_get_arg(0));
        return ob_get_clean();
    }
}
