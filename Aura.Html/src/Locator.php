<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html;

/**
 * 
 * A ServiceLocator implementation for loading and retaining helper objects.
 * 
 * @package Aura.Html
 * 
 */
class Locator
{
    /**
     * 
     * A registry to retain helper objects.
     * 
     * @var array
     * 
     */
    protected $registry;

    /**
     * 
     * Tracks whether or not a registry entry has been converted from a 
     * callable to a helper object.
     * 
     * @var array
     * 
     */
    protected $converted = [];
    
    /**
     * 
     * Constructor.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * helper name and the value is a callable that returns a helper object.
     * 
     */
    public function __construct(array $registry = [])
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }

    /**
     * 
     * Magic call to make the registry objects available as methods.
     * 
     * @param string $name A registered helper name.
     * 
     * @param array $params Params to pass to the helper.
     * 
     * @return mixed
     * 
     */
    public function __call($name, $params)
    {
        $helper = $this->get($name);
        return call_user_func_array($helper, $params);
    }
    
    /**
     * 
     * Sets a helper into the registry by name.
     * 
     * @param string $name The helper name.
     * 
     * @param callable $spec A callable that returns a helper object.
     * 
     * @return void
     * 
     */
    public function set($name, callable $spec)
    {
        $this->registry[$name] = $spec;
        $this->converted[$name] = false;
    }

    /**
     * 
     * Gets a helper object from the registry by name.
     * 
     * @param string $name The helper to retrieve.
     * 
     * @return AbstractHelper
     * 
     */
    public function get($name)
    {
        if (! isset($this->registry[$name])) {
            throw new Exception\HelperNotFound($name);
        }

        if (! $this->converted[$name]) {
            $func = $this->registry[$name];
            $this->registry[$name] = $func();
            $this->converted[$name] = true;
        }

        return $this->registry[$name];
    }
}
