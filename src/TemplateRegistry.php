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
class TemplateRegistry
{
    /**
     *
     * The map of registered templates.
     *
     * @var array
     *
     */
    protected $map = array();

    /**
     *
     * Constructor.
     *
     * @param array $map A map of templates.
     *
     */
    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    /**
     *
     * Registers a template.
     *
     * If the template is a string, it is treated as a path to a PHP include
     * file, and gets wrapped inside a closure that includes that file.
     * Otherwise the template is treated as a callable.
     *
     * @param string $name Register the template under this name.
     *
     * @param string|callable $spec A string path to a PHP include file, or a
     * callable.
     *
     * @return null
     *
     */
    public function set($name, $spec)
    {
        if (is_string($spec)) {
            $__FILE__ = $spec;
            $spec = function (array $__VARS__ = array()) use ($__FILE__) {
                extract($__VARS__, EXTR_SKIP);
                require $__FILE__;
            };
        }
        $this->map[$name] = $spec;
    }

    /**
     *
     * Is a named template registered?
     *
     * @param string $name The template name.
     *
     * @return bool
     *
     */
    public function has($name)
    {
        return isset($this->map[$name]);
    }

    /**
     *
     * Gets a template from the registry.
     *
     * @param string $name The template name.
     *
     * @return callable
     *
     */
    public function get($name)
    {
        if (! $this->has($name)) {
            throw new Exception\TemplateNotFound($name);
        }

        return $this->map[$name];
    }
}
