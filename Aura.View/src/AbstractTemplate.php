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
        $data = []
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
     * @param array $data An array of key-value pairs where the keys are 
     * template variable names, and the values are the variable values.
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
     * Merges new data with the existing template data.
     * 
     * @param array $data An array of key-value pairs where the keys are 
     * template variable names, and the values are the variable values.
     * 
     * @return void
     * 
     */
    public function addData($data)
    {
        $this->data = (object) array_merge(
            (array) $this->data,
            (array) $data
        );
    }

    /**
     * 
     * Gets all template variables.
     * 
     * @return array An array of key-value pairs where the keys are 
     * template variable names, and the values are the variable values.
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
     * Renders the named template and returns its output.
     * 
     * @param string $name The template name to look for in the factory.
     * 
     * @return string The template output.
     * 
     */
    public function template($name)
    {
        $template = $this->factory->newInstance($name, $this->helper, $this->data);
        return $this->render($template);
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
        $partial = $this->factory->newInstance($name, $this->helper, $data);
        return $this->render($partial);
    }
    
    /**
     * 
     * Renders a template by invoking it inside an output buffer.
     * 
     * @param callable $tpl The template to invoke.
     * 
     * @return string The template output.
     * 
     */
    private function render(callable $tpl)
    {
        ob_start();
        $tpl();
        return ob_get_clean();
    }
}
