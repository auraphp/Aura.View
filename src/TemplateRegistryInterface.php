<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.View
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\View;

/**
 *
 * A registry for templates.
 *
 * @package Aura.View
 *
 */
interface TemplateRegistryInterface
{
    /**
     *
     * Check if template exists
     *
     * @param string $name The template name.
     *
     * @return bool
     *
     */
    public function has($name);

    /**
     *
     * Gets a template
     *
     * @param string $name The template name.
     *
     * @return \Closure
     *
     * @throws Exception\TemplateNotFound
     *
     */
    public function get($name);
}
