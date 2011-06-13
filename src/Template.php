<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

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
     * @param string $__name__ The template name to use.
     * 
     * @param array $__vars__ Variables to extract into the local scope.
     * 
     * @return string
     * 
     */
    public function fetch($__name__, array $__vars__ = array())
    {
        if ($__vars__) {
            extract($__vars__, EXTR_SKIP);
        }
        ob_start();
        require $this->find($__name__);
        return ob_get_clean();
    }
}
