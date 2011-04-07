<?php
namespace aura\view;

/**
 * 
 * Provides a Template View pattern implementation for Aura.
 * 
 * This implementation is good for all (X)HTML and XML template
 * formats, and provides a built-in escaping mechanism for values,
 * along with lazy-loading and persistence of helper objects.
 * 
 * Also supports "partial" templates with variables extracted within
 * the partial-template scope.
 * 
 * @package aura.view
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
abstract class AbstractTemplate
{
    /**
     * 
     * View "finder" (to find views in a path stack).
     * 
     * @var Finder
     * 
     */
    private $_finder;
    
    /**
     * 
     * Data assigned to the template.
     * 
     * @var array
     * 
     */
    private $_data = array();
    
    /**
     * 
     * A registry for helper objects, so that repeated calls to the same 
     * helper use the same object.
     * 
     * @var HelperRegistry
     * 
     */
    private $_helper_registry;
    
    /**
     * 
     * 
     */
    public function __construct(
        Finder $finder,
        HelperRegistry $helper_registry
    ) {
        $this->_finder = $finder;
        $this->_helper_registry = $helper_registry;
    }
    
    public function __get($key)
    {
        return $this->_data[$key];
    }
    
    public function __set($key, $val)
    {
        $this->_data[$key] = $val;
    }
    
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }
    
    public function __unset($key)
    {
        unset($this->_data[$key]);
    }
    
    public function setPaths(array $paths = array())
    {
        $this->_finder->setPaths($paths);
    }
    
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * 
     * Returns the path to the requested template script.
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
        $file = $this->_finder->find($name);
        if (! $file) {
            throw new Exception_TemplateNotFound($name);
        }
        
        // done!
        return $file;
    }
    
    public function getHelper($class)
    {
        return $this->_helper_registry->getInstance($class);
    }
    
    public function newHelper($class)
    {
        return $this->_helper_registry->newInstance($class);
    }
    
    abstract public function fetch($name, array $vars = array());
}
