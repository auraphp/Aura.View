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
     * Object to to find rendering code for the template.
     * 
     * @var Finder
     * 
     */
    private $finder;

    /**
     * 
     * Data assigned to the template.
     * 
     * @var object
     * 
     */
    protected $data;

    /**
     * 
     * An aribtrary object for helper methods.
     * 
     * @var object
     * 
     */
    private $helper;

    public function __construct()
    {
        $this->data = (object) [];
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
     * Sets the template data object.
     * 
     * @param mixed $data An array or object where the keys or properties are
     * variable names, and the corresponding values are the variable values.
     * (This param is cast to an object.)
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
     * Sets the finder object.
     * 
     * @param Finder $finder A template finder.
     * 
     * @return void
     * 
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * 
     * Returns the finder object.
     * 
     * @return object
     * 
     */
    public function getFinder()
    {
        return $this->finder;
    }
}
