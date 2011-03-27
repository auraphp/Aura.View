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
 * @category Aura
 * 
 * @package Aura_View PHP-based TemplateView system.
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */
abstract class TemplateBase
{
    /**
     * 
     * Parameters for escaping.
     * 
     * @var array
     * 
     */
    private $_escape_charset = 'UTF-8';
    
    private $_escape_quotes = ENT_COMPAT;
    
    /**
     * 
     * View "finder" (to find views in a directory stack).
     * 
     * @var Finder
     * 
     */
    private $_finder;
    
    private $_data = array();
    
    public function __construct(
        PluginRegistry $plugin_registry,
        Finder $finder
    ) {
        $this->_helper_registry = $helper_registry;
        $this->_finder = $finder;
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
    
    /**
     * 
     * Executes a helper method with arbitrary parameters.
     * 
     * @param string $name The helper name.
     * 
     * @param array $args The parameters passed to the helper.
     * 
     * @return string The helper output.
     * 
     */
    public function __call($name, $args)
    {
        $plugin = $this->_plugin_registry->get($name);
        return call_user_func_array(array($plugin, '__invoke'), $args);
    }
    
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function setEscapeQuotes($quotes)
    {
        $this->_escape_quotes = $quotes;
    }
    
    public function setEscapeCharset($charset)
    {
        $this->_escape_charset = $charset;
    }
    
    /**
     * 
     * Built-in helper for escaping output.
     * 
     * @param scalar $value The value to escape.
     * 
     * @return string The escaped value.
     * 
     */
    public function escape($value)
    {
        return htmlspecialchars(
            $value,
            $this->_escape_quotes,
            $this->_escape_charset
        );
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
    
    abstract public function fetch($name, array $vars = array());
}
