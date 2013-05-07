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
     * View "finder" (to find views in a path stack).
     * 
     * @var TemplateFinder
     * 
     */
    private $template_finder;

    /**
     * 
     * Data assigned to the template.
     * 
     * @var object
     * 
     */
    private $data;

    /**
     * 
     * A ServiceLocator for helper objects, so that repeated calls to the same 
     * helper use the same object.
     * 
     * @var HelperLocator
     * 
     */
    private $helper_locator;

    /**
     * 
     * A data-escaper object.
     * 
     * @var Escaper\Object
     * 
     */
    private $escaper;

    /**
     * 
     * A factory to create an escaper.
     * 
     * @var EscaperFactory
     * 
     */
    private $escaper_factory;

    /**
     * 
     * Constructor.
     * 
     * @param EscaperFactory $escaper_factory An escaper factory.
     * 
     * @param TemplateFinder $template_finder A template finder.
     * 
     * @param HelperLocator $helper_locator A Service Locator for helpers
     * attached to this template.
     * 
     */
    public function __construct(
        EscaperFactory $escaper_factory,
        TemplateFinder $template_finder,
        HelperLocator  $helper_locator
    ) {
        $this->escaper_factory = $escaper_factory;
        $this->template_finder = $template_finder;
        $this->helper_locator  = $helper_locator;
        $this->setData();
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
        return $this->escaper->$key;
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
     * Magic call to provide shared helpers as template methods.
     * 
     * @param string $name The helper name.
     * 
     * @param array $args The arguments to pass to the helper.
     * 
     * @return void
     * 
     */
    public function __call($name, $args)
    {
        $helper = $this->getHelper($name);
        return call_user_func_array($helper, $args);
    }

    /**
     * 
     * Sets the search paths for templates; paths are searched in FIFO order.
     * 
     * @param array $paths An array of directory paths where templates are.
     * 
     * @return void
     * 
     */
    public function setPaths(array $paths = [])
    {
        $this->template_finder->setPaths($paths);
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
    public function addData(array $data = [])
    {
        $this->data    = (object) array_merge((array) $this->data, $data);
        $this->escaper = $this->escaper_factory->newInstance($this->data);
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
    public function setData(array $data = [])
    {
        $this->data    = (object) $data;
        $this->escaper = $this->escaper_factory->newInstance($this->data);
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
        return (array) $this->data;
    }

    /**
     * 
     * Returns the raw data object.
     * 
     * @return \StdClass
     * 
     */
    public function __raw()
    {
        return $this->data;
    }

    /**
     * 
     * Returns the helper locator object.
     * 
     * @return HelperLocator
     * 
     */
    public function getHelperLocator()
    {
        return $this->helper_locator;
    }

    /**
     * 
     * Returns the path to the requested template script; searches through
     * $this->paths to find the first matching template.
     * 
     * @param string $name The template name to look for in the template path.
     * 
     * @return string The full path to the template script.
     * 
     */
    public function find($name)
    {
        // append ".php" if needed
        if (substr($name, -4) != '.php') {
            $name .= '.php';
        }

        // find the path to the template
        $file = $this->template_finder->find($name);
        if (! $file) {
            throw new Exception\TemplateNotFound($name);
        }

        // done!
        return $file;
    }

    /**
     * 
     * Returns the TemplateFinder object.
     * 
     * @return TemplateFinder
     * 
     */
    public function getTemplateFinder()
    {
        return $this->template_finder;
    }

    /**
     * 
     * Retrieves a shared helper from the helper container.
     * 
     * @param string $name The helper to retrieve.
     * 
     * @return mixed
     * 
     */
    public function getHelper($name)
    {
        return $this->helper_locator->get($name);
    }

    /**
     * 
     * Fetches the output from a template.
     * 
     * @param string $__name__ The template name to use.
     * 
     * @return string
     * 
     */
    abstract public function fetch($__name__);

    /**
     * 
     * Fetches the output from a partial. The partial will be executed in
     * isolation from the rest of the template, which means `$this` refers
     * to the *partial* data, not the original template data. However,
     * helper objects are shared.
     * 
     * @param string $name The partial to use.
     * 
     * @param array $data Data to use for the partial.
     * 
     * @return string
     * 
     */
    abstract public function partial($name, array $data = []);
}
