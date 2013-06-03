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
 * Finds template closures or class names from among namespace prefixes.
 * 
 * @package Aura.View
 * 
 */
class Finder
{
    /**
     * 
     * An array of closures to be used as named templates.
     * 
     * @var array
     * 
     */
    protected $closures = [];
    
    /**
     * 
     * The namespace prefixes.
     * 
     * @var array
     * 
     */
    protected $prefixes = [];

    /**
     * 
     * A cache of template objects, so we do not need to search for a template
     * more than once.
     * 
     * @var array
     * 
     */
    protected $registry = [];

    /**
     * 
     * Constructor.
     * 
     * @param array $prefixes The default prefixes for this Finder.
     * 
     */
    public function __construct(
        array $closures = [],
        array $prefixes = []
    ) {
        $this->setClosures($closures);
        $this->setPrefixes($prefixes);
    }

    public function setClosures(array $closures)
    {
        $this->registry = [];
        $this->closures = [];
        foreach ($closures as $name => $closure) {
            $this->setClosure($name, $closure);
        }
    }
    
    public function setClosure($name, Closure $closure)
    {
        unset($this->registry[$name]);
        $this->closures[$name] = $closure;
    }
    
    public function getClosures()
    {
        return $this->closures;
    }
    
    /**
     * 
     * Sets the prefixes directly.
     * 
     * {{code: php
     *      $finder->set([
     *          'Foo\Bar\Baz',
     *          'Foo\Bar',
     *          'Foo',
     *      ]);
     *      // $finder->get() reveals that the search order will be
     *      // 'Foo\Bar\Baz', 'Foo\Bar', 'Foo'.
     * }}
     * 
     * @param array|string $prefixes The directories to add to the prefixes.
     * 
     * @return void
     * 
     */
    public function setPrefixes(array $prefixes)
    {
        $this->registry = [];
        $this->prefixes = $prefixes;
    }

    /**
     * 
     * Gets a copy of the current prefixes.
     * 
     * @return array
     * 
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * 
     * Adds one element to the top of the prefixes.
     * 
     * {{code: php
     *      $finder->unshift('Foo');
     *      $finder->unshift('Foo\Bar');
     *      $finder->unshift('Foo\Bar\Baz');
     *      // $finder->get() reveals that the search order will be
     *      // 'Foo\Bar\Baz', 'Foo\Bar', 'Foo'.
     * }}
     * 
     * @param array|string $prefix The directories to add to the prefixes.
     * 
     * @return void
     * 
     */
    public function unshiftPrefix($prefix)
    {
        $this->registry = [];
        array_unshift($this->prefixes, rtrim($prefix, '\\'));
    }

    /**
     * 
     * Adds one element to the end of the prefixes.
     * 
     * {{code: php
     *      $finder->push('Foo\Bar\Baz');
     *      $finder->push('Foo\Bar');
     *      $finder->push('Foo');
     *      // $finder->get() reveals that the search order will be
     *      // 'Foo\Bar\Baz', 'Foo\Bar', 'Foo'.
     * }}
     * 
     * @param array|string $prefix The directories to add to the prefixes.
     * 
     * @return void
     * 
     */
    public function pushPrefix($prefix)
    {
        $this->registry = [];
        array_push($this->prefixes, rtrim($prefix, '\\'));
    }

    public function popPrefix()
    {
        $this->registry = [];
        return array_pop($this->prefixes);
    }
    
    public function shiftPrefix()
    {
        $this->registry = [];
        return array_shift($this->prefixes);
    }
    
    /**
     * 
     * Finds a class using the prefixes.
     * 
     * {{code: php
     *      $finder->set(['Foo\Bar\Baz', 'Foo\Bar', 'Foo']);
     *      $class = $finder->find('IndexView');
     *      // $name is now the first instance of 'IndexView' registry from the         
     *      // assigned prefixes, looking first for 'Foo\Bar\Baz\IndexView', then
     *      // for 'Foo\Bar\IndexView', then finally for 'Foo\IndexView'.
     * }}
     * 
     * @param string $name The class to find using the prefix prefixes.
     * 
     * @return mixed The absolute path to the file, or false if not
     * registry using the prefixes.
     * 
     */
    public function find($name)
    {
        // is the name already in the registry?
        if (isset($this->registry[$name])) {
            return $this->registry[$name];
        }

        // is the name relative?
        foreach ($this->prefixes as $prefix) {
            $class = $prefix . '\\' . $name;
            $result = $this->exists($class);
            if ($result) {
                $this->registry[$name] = $result;
                return $result;
            }
        }

        // is the name absolute?
        $result = $this->exists($name);
        if ($result) {
            $this->registry[$name] = $result;
            return $result;
        }
        
        // did not find it
        return false;
    }
    
    public function exists($class)
    {
        // closures take precedence
        if (isset($this->closures[$class])) {
            return $this->closures[$class];
        }
        
        // look for a class file
        if (class_exists($class)) {
            return $class;
        }
    }
}
