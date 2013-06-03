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
 * Provides an abstract TemplateView pattern implementation. We use an 
 * abstract so that the extended "real" Template class does not have access
 * to the private support properties herein.
 * 
 * @package Aura.View
 * 
 */
abstract class AbstractTemplate
{
    /**
     * 
     * Object to to find views in a path stack.
     * 
     * @var Finder
     * 
     */
    private $factory;

    /**
     * 
     * Data assigned to the template.
     * 
     * @var array
     * 
     */
    private $data;

    /**
     * 
     * An aribtrary object for helper methods.
     * 
     * @var object
     * 
     */
    private $helper;

    /**
     * 
     * Constructor.
     * 
     * @param Finder $finder A template finder.
     * 
     * @param object $helper An arbitrary object for helper methods exposed as
     * methods on this template object.
     * 
     */
    public function __construct(
        Factory $factory,
        $helper,
        $data = null
    ) {
        $this->setFactory($factory);
        $this->setHelper($helper);
        $this->setData($data);
    }

    /**
     * 
     * Magic read access to template data.
     * 
     * @param string $key The template variable name.
     * 
     * @return mixed
     * 
     */
    public function __get($key)
    {
        return $this->data->$key;
    }

    /**
     * 
     * Magic write access to template data.
     * 
     * @param string $key The template variable name.
     * 
     * @param string $val The template variable value.
     * 
     * @return mixed
     * 
     */
    public function __set($key, $val)
    {
        $this->data->$key = $val;
    }

    /**
     * 
     * Magic isset() checks on template data.
     * 
     * @param string $key The template variable name.
     * 
     * @return bool
     * 
     */
    public function __isset($key)
    {
        return isset($this->data->$key);
    }

    /**
     * 
     * Magic unset() for template data.
     * 
     * @param string $key The template variable name.
     * 
     * @return void
     * 
     */
    public function __unset($key)
    {
        unset($this->data->$key);
    }

    /**
     * 
     * Magic call to expose helper object methods as template methods.
     * 
     * @param string $name The helper object method name.
     * 
     * @param array $args The arguments to pass to the helper.
     * 
     * @return void
     * 
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->helper, $method], $params);
    }

    /**
     * 
     * Replaces all template data at once; this will remove all previous
     * data.
     * 
     * @param mixes $data An array or object where the keys or properties are
     * variable names, and the corresponding values are the variable values.
     * (Arrays will be converted to objects.)
     * 
     * @return void
     * 
     */
    public function setData($data)
    {
        $this->data = (object) $data;
    }

    /**
     * 
     * Gets the template data object.
     * 
     * @return object
     * 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 
     * Sets the helper object.
     * 
     * @param object $helper The helper object.
     * 
     * @param void
     * 
     */
    public function setHelper($helper)
    {
        if (! is_object($helper)) {
            throw new Exception\InvalidHelper("The helper must be an object.");
        }
        $this->helper = $helper;
    }

    /**
     * 
     * Returns the helper object.
     * 
     * @return object
     * 
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * 
     * Sets the factory object.
     * 
     * @param Factory $factory A template factory.
     * 
     * @return void
     * 
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * 
     * Returns the factory object.
     * 
     * @return object
     * 
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * 
     * Renders a sub-template and returns its output, optionally with 
     * scope-separated data.
     * 
     * @param string $name The template to render to use.
     * 
     * @param mixed $data Scope-separated data to use in the template. Passing
     * data here means the main template data *will not* be available, but
     * leaving it empty means the main template data *will* be available.
     * 
     * @return string
     * 
     */
    public function render($name, $data = null)
    {
        // use the main data, or scope-separated data?
        if (! $data) {
            $data = $this->data;
        }
        
        // get the template
        $template = $this->factory->newInstance($name, $this->helper, $data);
        if (! $template) {
            throw new Exception\TemplateNotFound($name);
        }
        
        // render and return the sub-template
        ob_start();
        $template();
        return ob_get_clean();
    }
}
