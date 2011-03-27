<?php
namespace aura\view;

/**
 * 
 * Finds files in user-defined path hierarchies.
 * 
 * As you add directory paths, they are searched first when you call
 * find($file).  This allows users to add override paths so their files will
 * be used instead of default files.
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
    
    // cache of found files
    protected $found = array();
    
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
    
    /**
     * 
     * Adds one path to the paths.
     * 
     * {{code: php
     *     $finder->addPath('path/1');
     *     $finder->addPath('path/2');
     *     $finder->addPath('path/3');
     *     // $finder->get() reveals that the directory search order will be
     *     // 'path/3/', 'path/2/', 'path/1/', because the later adds
     *     // override the newer ones.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function addPath($path)
    {
        array_unshift($this->paths, rtrim($path, DIRECTORY_SEPARATOR));
        $this->found = array();
    }
    
    /**
     * 
     * Sets the paths directly.
     * 
     * {{code: php
     *      $finder->addPaths(array(
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
