<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
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
 * @package Aura.View
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
     * @return string
     * 
     */
    public function fetch($__name__)
    {
        ob_start();
        require $this->find($__name__);
        return ob_get_clean();
    }

    /**
     * 
     * Fetches the output from a partial. The partial will be executed in
     * isolation from the rest of the template, which means `$this` refers
     * to the *partial* data, not the original template data. However, helper
     * objects *are* shared between the original template and the partial.
     * 
     * @param string $name The partial to use.
     * 
     * @param array $data Data to use for the partial.
     * 
     * @return string
     * 
     */
    public function partial($name, array $data = [])
    {
        $tpl = clone($this);
        $tpl->setData($data);
        return $tpl->fetch($name);
    }
}
