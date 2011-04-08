<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace aura\view;

/**
 * 
 * Finds files in user-defined path hierarchies.
 * 
 * @package aura.view
 * 
 */
class Finder
{
    /**
     * 
     * The stack of paths.
     * 
     * @var array
     * 
     */
    protected $paths = array();
    
    /**
     * 
     * A cache of found files, so we do not need to search the path stack
     * for a particular file more than once.
     * 
     * @var array
     * 
     */
    protected $found = array();
    
    /**
     * 
     * Constructor.
     * 
     * @param array $paths The default path stack for this Finder.
     * 
     */
    public function __construct(array $paths = array())
    {
        $this->paths = $paths;
    }
    
    /**
     * 
     * Gets a copy of the current path stack.
     * 
     * @return array
     * 
     */
    public function getPaths()
    {
        return $this->paths;
    }
    
    /**
     * 
     * Adds one path to the top of the path stack.
     * 
     * {{code: php
     *     $finder->unshiftPath('path/1');
     *     $finder->unshiftPath('path/2');
     *     $finder->unshiftPath('path/3');
     *     // $finder->get() reveals that the directory search order will be
     *     // 'path/3/', 'path/2/', 'path/1/'.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function unshiftPath($path)
    {
        array_unshift($this->paths, rtrim($path, DIRECTORY_SEPARATOR));
        $this->found = array();
    }
    
    /**
     * 
     * Adds one path to the end of the path stack.
     * 
     * {{code: php
     *     $finder->unshiftPath('path/1');
     *     $finder->unshiftPath('path/2');
     *     $finder->unshiftPath('path/3');
     *     // $finder->get() reveals that the directory search order will be
     *     // 'path/1/', 'path/2/', 'path/3/'.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function pushPath($path)
    {
        $this->paths[] = rtrim($path, DIRECTORY_SEPARATOR);
        $this->found = array();
    }
    
    /**
     * 
     * Sets the paths directly.
     * 
     * {{code: php
     *      $finder->unshiftPaths(array(
     *          'path/1',
     *          'path/2',
     *          'path/3',
     *      ));
     *      // $finder->get() reveals that the search order will be
     *      // 'path/1/', 'path/2/', 'path/3/'.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
        $this->found = array();
    }
    
    /**
     * 
     * Finds a file in the paths.
     * 
     * Relative paths are honored as part of the include_path.
     * 
     * {{code: php
     *     $finder->add('path/1');
     *     $finder->add('path/2');
     *     $finder->add('path/3');
     *     
     *     $file = $finder->find('file.php');
     *     // $file is now the first instance of 'file.php' found from the         
     *     // directory paths, looking first in 'path/3/file.php', then            
     *     // 'path/2/file.php', then finally 'path/1/file.php'.
     * }}
     * 
     * @param string $file The file to find using the directory paths
     * and the include_path.
     * 
     * @return mixed The absolute path to the file, or false if not
     * found using the paths.
     * 
     */
    public function find($file)
    {
        if (isset($this->found[$file])) {
            return $this->found[$file];
        }
        
        foreach ($this->paths as $path) {
            try {
                $spec = $path . DIRECTORY_SEPARATOR . $file;
                $obj = new \SplFileObject($spec, 'r', true);
                $this->found[$file] = $obj->getRealPath();
                return $this->found[$file];
            } catch (\RuntimeException $e) {
                continue;
            }
        }
        return false;
    }
}
