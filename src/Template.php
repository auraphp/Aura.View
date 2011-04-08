<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace aura\view;

/**
 * 
 * Provides a concrete Template View pattern implementation; it does not
 * have access to the private support properties of the parent abstract.
 * 
 * @package aura.view
 * 
 */
class Template extends AbstractTemplate
{
    /**
     * 
     * Fetches the output from a template.
     * 
     * @param string $name The template name to use.
     * 
     * @param array $vars Variables to extract into the local scope.
     * 
     * @return string
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
