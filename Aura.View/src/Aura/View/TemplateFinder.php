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
 * Finds files in user-defined path hierarchies.
 * 
 * @package Aura.View
 * 
 */
class TemplateFinder
{
    /**
     * 
     * The stack of paths.
     * 
     * @var array
     * 
     */
    protected $paths = [];

    /**
     * 
     * A cache of found files, so we do not need to search the path stack
     * for a particular file more than once.
     * 
     * @var array
     * 
     */
    protected $found = [];

    /**
     * 
     * Constructor.
     * 
     * @param array $paths The default path stack for this TemplateFinder.
     * 
     */
    public function __construct(array $paths = [])
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
     *     $template_finder->prepend('/path/1');
     *     $template_finder->prepend('/path/2');
     *     $template_finder->prepend('/path/3');
     *     // $template_finder->getPaths() reveals that the directory search order will be
     *     // '/path/3/', '/path/2/', '/path/1/'.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function prepend($path)
    {
        array_unshift($this->paths, rtrim($path, DIRECTORY_SEPARATOR));
        $this->found = [];
    }

    /**
     * 
     * Adds one path to the end of the path stack.
     * 
     * {{code: php
     *     $template_finder->append('/path/1');
     *     $template_finder->append('/path/2');
     *     $template_finder->append('/path/3');
     *     // $template_finder->getPaths() reveals that the directory search order will be
     *     // '/path/1/', '/path/2/', '/path/3/'.
     * }}
     * 
     * @param array|string $path The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function append($path)
    {
        $this->paths[] = rtrim($path, DIRECTORY_SEPARATOR);
        $this->found = [];
    }

    /**
     * 
     * Sets the paths directly.
     * 
     * {{code: php
     *      $template_finder->setPaths([
     *          '/path/1',
     *          '/path/2',
     *          '/path/3',
     *      ]);
     *      // $template_finder->getPaths() reveals that the search order will be
     *      // '/path/1', '/path/2', '/path/3'.
     * }}
     * 
     * @param array|string $paths The directories to add to the paths.
     * 
     * @return void
     * 
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
        $this->found = [];
    }

    /**
     * 
     * Finds a file in the paths.
     * 
     * {{code: php
     *     $template_finder->append('/path/1');
     *     $template_finder->append('/path/2');
     *     $template_finder->append('/path/3');
     *     
     *     $file = $template_finder->find('file.php');
     *     // $file is now the first instance of 'file.php' found from the         
     *     // assigned paths, looking first for '/path/3/file.php', then for
     *     // '/path/2/file.php', then finally for '/path/1/file.php'.
     * }}
     * 
     * @param string $file The file to find using the assigned paths.
     * 
     * @return mixed The absolute path to the file, or false if not
     * found using the paths.
     * 
     */
    public function find($file)
    {
        // is the file location cached?
        if (isset($this->found[$file])) {
            return $this->found[$file];
        }

        // is the file in the assigned paths?
        foreach ($this->paths as $path) {
            $found = $this->exists($path . DIRECTORY_SEPARATOR . $file);
            if ($found) {
                $this->found[$file] = $found;
                return $found;
            }
        }

        // can we find it directly?
        $found = $this->exists($file);
        if ($found) {
            $this->found[$file] = $found;
            return $found;
        }

        // never found it
        return false;
    }

    /**
     * 
     * Checks to see if a file exists at a particular location.
     * 
     * @param string $file The file to find.
     * 
     * @return mixed The absolute path to the file, or false if not
     * found.
     * 
     */
    protected function exists($file)
    {
        if (is_readable($file)) {
            return $file;
        } else {
            return false;
        }
    }
}
