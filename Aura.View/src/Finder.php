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

use Closure;

/**
 * 
 * Finds template specifications, whether registered directly or from among
 * class paths.
 * 
 * @package Aura.View
 * 
 */
class Finder
{
    /**
     * 
     * A set of named templates, either as closures or as absolute file paths.
     * These take precedence over file system lookups.
     * 
     * @var array
     * 
     */
    protected $names = [];
    
    /**
     * 
     * Paths to templates for classes.
     * 
     * @var array
     * 
     */
    protected $paths = [];

    /**
     * 
     * Templates that have been found, to avoid repeated lookups.
     * 
     * @var array
     * 
     */
    protected $found = [];
    
    /**
     * 
     * The logical separator for template name segments.
     * 
     * @param string
     * 
     */
    protected $separator = '\\';
    
    /**
     * 
     * Constructor.
     * 
     * @param array $paths The default paths for this Finder.
     * 
     */
    public function __construct(
        array $names = [],
        array $paths = []
    ) {
        $this->setNames($names);
        $this->setPaths($paths);
    }

    public function setNames(array $names)
    {
        $this->found = [];
        $this->names = [];
        foreach ($names as $name => $spec) {
            $this->setName($name, $spec);
        }
    }

    // save the user from himself and replace DIRECTORY_SEPARATOR with
    // $this->separator?
    public function setName($name, $spec)
    {
        // force a leading backslash
        $name = $this->separator . ltrim($name, $this->separator);
        // retain it
        $this->names[$name] = $spec;
        // unset found template of the name name
        unset($this->found[$name]);
    }

    public function getNames()
    {
        return $this->names;
    }
    
    /**
     * 
     * Sets file system paths for logical prefixes (e.g. class names).
     * 
     * {{code: php
     *      $finder->set([
     *          'Foo'         => '/path/to/Foo/views',
     *          'Foo\Bar'     => '/path/to/Foo/Bar/views',
     *          'Foo\Bar\Baz' => '/path/to/Foo/Bar/Baz/views',
     *      ]);
     * }}
     * 
     * @param array|string $paths The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function setPaths(array $paths)
    {
        $this->found = [];
        foreach ($paths as $class => $path) {
            $this->setPath($class, $path);
        }
    }

    public function setPath($prefix, $path)
    {
        // force a leading backslash and trailing backslash
        $prefix = $this->separator . ltrim($prefix, $this->separator);
        $prefix = rtrim($prefix, $this->separator) . $this->separator;
        
        // force a trailing directory separator
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        // retain it
        $this->paths[$prefix] = $path;
        
        // unset all found templates
        $this->found = [];
    }
    
    /**
     * 
     * Gets a copy of the current paths.
     * 
     * @return array
     * 
     */
    public function getPaths()
    {
        return $this->paths;
    }

    public function getFound()
    {
        return $this->found;
    }
    
    public function setPrefixes($prefixes)
    {
        $this->prefixes = $prefixes;
        $this->found = [];
    }
    
    /**
     * 
     * Finds a template specification.
     * 
     * {{code: php
     *      $finder->set(['Foo\Bar\Baz', 'Foo\Bar', 'Foo']);
     *      $class = $finder->find('IndexView');
     *      // $name is now the first instance of 'IndexView' found from the         
     *      // assigned paths, looking first for 'Foo\Bar\Baz\IndexView', then
     *      // for 'Foo\Bar\IndexView', then finally for 'Foo\IndexView'.
     * }}
     * 
     * @param string $name The class to find using the prefix paths.
     * 
     * @return mixed The template spec: a string path to a PHP script, or a
     * closure.
     * 
     */
    public function find($suffix)
    {
        // get a copy of the assigned prefixes
        $prefixes = $this->prefixes;
        
        while ($prefixes) {
            // get the next prefix (FIFO)
            $prefix = array_shift($prefixes);
            
            // normalize it
            $prefix = $this->separator . ltrim($prefix, $this->separator);
            $prefix = rtrim($prefix, $this->separator) . $this->separator;
            
            // does the template exist in the prefix?
            $found = $this->exists($prefix, $suffix);
            if ($found) {
                // yes, return it
                return $found;
            }
        }
        
        // look for it without a prefix
        $found = $this->exists(null, $suffix);
        if ($found) {
            return $found;
        }
        
        // never found it
        return false;
    }
    
    public function exists($prefix, $suffix)
    {
        // create the name we're going to look for
        $name = $prefix . $suffix;
        
        // has it been found previously?
        if (isset($this->found[$name])) {
            // yes, return it
            return $this->found[$name];
        }
        
        // has it been set directly?
        if (isset($this->names[$name])) {
            // yes, mark it as found and return it
            $this->found[$name] = $this->names[$name];
            return $this->found[$name];
        }
        
        // is a path available for the prefix?
        if (isset($this->paths[$prefix])) {
            // does a .php file exist for suffix? note that logical separators
            // in the suffix get converted to directory separators.
            $file = $this->paths[$prefix]
                  . str_replace($this->separator, DIRECTORY_SEPARATOR, $suffix)
                  . '.php';
            if ($this->isReadable($file)) {
                $this->found[$name] = $file;
                return $this->found[$name];
            }
        }
        
        // does not exist
        return false;
    }
    
    protected function isReadable($file)
    {
        return is_readable($file);
    }
}
