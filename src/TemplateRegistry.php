<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/MIT-license.php MIT
 *
 */
declare(strict_types=1);

namespace Aura\View;

/**
 *
 * A registry for templates, resolving names against an explicit map first and
 * a list of search paths second.
 *
 * @package Aura.View
 *
 */
class TemplateRegistry implements TemplateRegistryInterface, SearchPathInterface
{
    /**
     *
     * The map of explicit template names and locations.
     *
     * @var array<string, \Closure>
     *
     */
    protected array $map = [];

    /**
     *
     * The paths to search for implicit template names.
     *
     * @var list<string>
     *
     */
    protected array $paths = [];

    /**
     *
     * The namespaced paths to search for implicit template names.
     *
     * @var array<string, list<string>>
     *
     */
    protected array $namespaces = [];

    /**
     *
     * Templates found in the search paths.
     *
     * @var array<string, \Closure>
     *
     */
    protected array $found = [];

    /**
     *
     * File extension to use when searching the path list for templates.
     *
     */
    protected string $templateFileExtension = '.php';

    /**
     *
     * Constructor.
     *
     * @param array<string, string|callable> $map A map of explicit template
     * names and locations.
     *
     * @param list<string> $paths A list of filesystem paths to search for
     * templates.
     *
     * @param array<string, list<string>> $namespaces A map of namespaces to
     * the filesystem paths to search for namespaced templates.
     *
     */
    public function __construct(
        array $map = [],
        array $paths = [],
        array $namespaces = []
    ) {
        foreach ($map as $name => $spec) {
            $this->set($name, $spec);
        }
        $this->setPaths($paths);
        $this->setNamespaces($namespaces);
    }

    /**
     *
     * Registers a template.
     *
     * If the template is a string, it is treated as a path to a PHP include
     * file, and gets wrapped inside a closure that includes that file.
     * Otherwise the template is treated as a callable and is normalized to a
     * \Closure, so that the view can bind `$this` to it.
     *
     * @param string $name Register the template under this name.
     *
     * @param string|callable $spec A string path to a PHP include file, or a
     * callable.
     *
     */
    public function set(string $name, string|callable $spec): void
    {
        if (is_string($spec)) {
            $this->map[$name] = $this->enclose($spec);
            return;
        }

        $this->map[$name] = $spec instanceof \Closure ? $spec : $spec(...);
    }

    /**
     *
     * Is a named template registered?
     *
     */
    public function has(string $name): bool
    {
        return isset($this->map[$name]) || $this->find($name);
    }

    /**
     *
     * Is a namespace registered?
     *
     */
    public function hasNamespace(string $namespace): bool
    {
        return isset($this->namespaces[$namespace]);
    }

    /**
     *
     * Gets a template from the registry.
     *
     * @throws Exception\TemplateNotFound when the name cannot be resolved.
     *
     */
    public function get(string $name): \Closure
    {
        if (isset($this->map[$name])) {
            return $this->map[$name];
        }

        if ($this->find($name)) {
            return $this->found[$name];
        }

        throw new Exception\TemplateNotFound($name);
    }

    /**
     *
     * Gets a copy of the current search paths.
     *
     * @return list<string>
     *
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     *
     * Gets a copy of the namespaced search paths, keyed on namespace.
     *
     * @return array<string, list<string>>
     *
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     *
     * Gets a copy of the search paths for one namespace.
     *
     *     $registry->appendPath('/path/1', 'blog');
     *     $registry->appendPath('/path/2', 'blog');
     *     // $registry->getNamespacePaths('blog') reveals that the search
     *     // order will be '/path/1', '/path/2'.
     *
     * An unregistered namespace has no paths, so it returns an empty array.
     *
     * @return list<string>
     *
     */
    public function getNamespacePaths(string $namespace): array
    {
        return $this->namespaces[$namespace] ?? [];
    }

    /**
     *
     * Adds one path to the top of the search paths.
     *
     *     $registry->prependPath('/path/1');
     *     $registry->prependPath('/path/2');
     *     $registry->prependPath('/path/3');
     *     // $this->getPaths() reveals that the directory search
     *     // order will be '/path/3/', '/path/2/', '/path/1/'.
     *
     * @param string $path The directory to add to the paths.
     *
     * @param string|null $namespace The directory namespace.
     *
     */
    public function prependPath(string $path, ?string $namespace = null): void
    {
        $this->found = [];
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if ($namespace !== null) {
            if (! $this->hasNamespace($namespace)) {
                $this->namespaces[$namespace] = [];
            }
            array_unshift($this->namespaces[$namespace], $path);
            return;
        }

        array_unshift($this->paths, $path);
    }

    /**
     *
     * Adds one path to the end of the search paths.
     *
     *     $registry->appendPath('/path/1');
     *     $registry->appendPath('/path/2');
     *     $registry->appendPath('/path/3');
     *     // $registry->getPaths() reveals that the directory search
     *     // order will be '/path/1/', '/path/2/', '/path/3/'.
     *
     * @param string $path The directory to add to the paths.
     *
     * @param string|null $namespace The directory namespace.
     *
     */
    public function appendPath(string $path, ?string $namespace = null): void
    {
        $this->found = [];
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if ($namespace !== null) {
            if (! $this->hasNamespace($namespace)) {
                $this->namespaces[$namespace] = [];
            }
            $this->namespaces[$namespace][] = $path;
            return;
        }

        $this->paths[] = $path;
    }

    /**
     *
     * Sets the paths directly.
     *
     *      $registry->setPaths([
     *          '/path/1',
     *          '/path/2',
     *          '/path/3',
     *      ]);
     *      // $registry->getPaths() reveals that the search order will
     *      // be '/path/1', '/path/2', '/path/3'.
     *
     * @param list<string> $paths The paths to set.
     *
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
        $this->found = [];
    }

    /**
     *
     * Sets the namespaces directly.
     *
     * @param array<string, list<string>> $namespaces A map of namespaces to
     * their search paths.
     *
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
        $this->found = [];
    }

    /**
     *
     * Sets the extension to be used when searching for templates via find().
     *
     */
    public function setTemplateFileExtension(string $templateFileExtension): void
    {
        $this->templateFileExtension = $templateFileExtension;
    }

    /**
     *
     * Finds a template in the search paths.
     *
     * @return bool True if found, false if not.
     *
     */
    protected function find(string $name): bool
    {
        if (isset($this->found[$name])) {
            return true;
        }

        if ($this->isNamespaced($name)) {
            return $this->findNamespaced($name);
        }

        foreach ($this->paths as $path) {
            $file = $path . DIRECTORY_SEPARATOR . $name . $this->templateFileExtension;
            if ($this->isReadable($file)) {
                $this->found[$name] = $this->enclose($file);
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Parses a namespaced template name.
     *
     * @return array{namespace?: string, name: string}
     *
     * @throws Exception\InvalidTemplateName if the template name is invalid.
     *
     */
    protected function parseName(string $name): array
    {
        $info  = explode('::', $name);
        $count = count($info);

        if ($count == 1) {
            return ['name' => $info[0]];
        }

        if ($count == 2) {
            return [
                'namespace' => $info[0],
                'name'      => $info[1],
            ];
        }

        throw new Exception\InvalidTemplateName('Invalid name: ' . $name);
    }

    /**
     *
     * Is the template name namespaced?
     *
     */
    protected function isNamespaced(string $name): bool
    {
        $info = $this->parseName($name);
        return isset($info['namespace']);
    }

    /**
     *
     * Finds a namespaced template in that namespace's search paths.
     *
     * @return bool True if found, false if not.
     *
     */
    protected function findNamespaced(string $name): bool
    {
        $info = $this->parseName($name);
        $namespace = $info['namespace'] ?? '';
        $shortname = $info['name'];

        if (! $this->hasNamespace($namespace)) {
            return false;
        }

        $paths = $this->namespaces[$namespace];

        foreach ($paths as $path) {
            $file = $path . DIRECTORY_SEPARATOR . $shortname . $this->templateFileExtension;
            if ($this->isReadable($file)) {
                $this->found[$name] = $this->enclose($file);
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Checks to see if a file is readable.
     *
     */
    protected function isReadable(string $file): bool
    {
        return is_readable($file);
    }

    /**
     *
     * Wraps a template file name in a \Closure.
     *
     * The `$__FILE__` and `$__VARS__` naming is deliberate: `extract()` runs in
     * this scope, and these names are unlikely to collide with a template
     * variable.
     *
     * @param string $__FILE__ The file name.
     *
     */
    protected function enclose(string $__FILE__): \Closure
    {
        return function (array $__VARS__ = []) use ($__FILE__): void {
            extract($__VARS__, EXTR_SKIP);
            require $__FILE__;
        };
    }
}
