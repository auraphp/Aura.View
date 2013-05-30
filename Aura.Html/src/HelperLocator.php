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
 * A ServiceHelperLocator implementation for loading and retaining helper objects.
 * 
 * @package Aura.Html
 * 
 */
class HelperLocator
{
    /**
     * 
     * A factory to create helper objects.
     * 
     * @var array
     * 
     */
    protected $helper_factory;

    /**
     * 
     * A registry of created helper objects.
     * 
     * @var array
     * 
     */
    protected $registry = [];
    
    /**
     * 
     * Constructor.
     * 
     * @param array $registry An array of key-value pairs where the key is the
     * helper name and the value is a callable that returns a helper object.
     * 
     */
    public function __construct(HelperFactory $helper_factory)
    {
        $this->helper_factory = $helper_factory;
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
     * Gets a helper object from the registry by name; creates it with the
     * helper factory if needed.
     * 
     * @param string $name The helper to retrieve.
     * 
     * @return AbstractHelper
     * 
     */
    public function get($name)
    {
        if (! isset($this->registry[$name])) {
            $this->registry[$name] = $this->helper_factory->newInstance($name);
        }

        return $this->registry[$name];
    }
}
