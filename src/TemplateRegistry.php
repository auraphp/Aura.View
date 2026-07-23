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
     * The search path directory each found template came from, keyed on the
     * same names as $found.
     *
     * @var array<string, string>
     *
     */
    protected array $foundIn = [];

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
     * Gets the search path directory a name resolved from.
     *
     * Returns null when the name came from the explicit map (which has no
     * search path behind it) or cannot be resolved at all. This answers "which
     * of the contributed directories won?" -- with several packages
     * contributing paths, that is otherwise unanswerable.
     *
     */
    public function getResolvedPath(string $name): ?string
    {
        if (isset($this->map[$name])) {
            return null;
        }

        if (! $this->find($name)) {
            return null;
        }

        return $this->foundIn[$name] ?? null;
    }

    /**
     *
     * Gets the next template of this name, resuming the search *after* a given
     * directory.
     *
     * This is what makes a shadowed template reachable. Ordinary resolution
     * stops at the first hit and the rest of the chain is lost, so a package
     * template can only be replaced wholesale; resuming from the path that
     * won lets the shadowing template render the one it shadowed.
     *
     * Returns null when nothing further in the chain has this name -- including
     * when $afterPath is not one of the search paths at all. A template that
     * turns out to shadow nothing is a normal state, not an error.
     *
     */
    public function getNext(string $name, string $afterPath): ?ResolvedTemplate
    {
        $info = $this->parseName($name);
        $namespace = $info['namespace'] ?? null;
        $shortname = $info['name'];

        $paths = $namespace === null
            ? $this->paths
            : $this->getNamespacePaths($namespace);

        $afterPath = rtrim($afterPath, DIRECTORY_SEPARATOR);
        $after = array_search($afterPath, $paths, true);

        if ($after === false) {
            return null;
        }

        foreach (array_slice($paths, $after + 1) as $path) {
            $file = $path . DIRECTORY_SEPARATOR . $shortname . $this->templateFileExtension;
            if ($this->isReadable($file)) {
                return new ResolvedTemplate($name, $this->enclose($file), $path);
            }
        }

        return null;
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
        $this->foundIn = [];
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
        $this->foundIn = [];
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
     * Trailing directory separators are stripped, as prependPath() and
     * appendPath() do.
     *
     * @param list<string> $paths The paths to set.
     *
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $this->normalizePaths($paths);
        $this->found = [];
        $this->foundIn = [];
    }

    /**
     *
     * Sets the namespaces directly.
     *
     * Trailing directory separators are stripped, as prependPath() and
     * appendPath() do.
     *
     * @param array<string, list<string>> $namespaces A map of namespaces to
     * their search paths.
     *
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = [];

        foreach ($namespaces as $namespace => $paths) {
            $this->namespaces[$namespace] = $this->normalizePaths($paths);
        }

        $this->found = [];
        $this->foundIn = [];
    }

    /**
     *
     * Strips trailing directory separators so that one directory has one
     * spelling inside the registry.
     *
     * This matters beyond tidiness: the path recorded for a found template is
     * handed straight back to getNext() to resume the search, so a directory
     * stored one way and compared another makes a shadowed template
     * unreachable -- parent() would go quiet rather than fail loudly.
     *
     * @param list<string> $paths
     *
     * @return list<string>
     *
     */
    protected function normalizePaths(array $paths): array
    {
        return array_map(
            static fn (string $path): string => rtrim($path, DIRECTORY_SEPARATOR),
            $paths
        );
    }

    /**
     *
     * Sets the extension to be used when searching for templates via find().
     *
     */
    public function setTemplateFileExtension(string $templateFileExtension): void
    {
        $this->templateFileExtension = $templateFileExtension;
        $this->found = [];
        $this->foundIn = [];
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
                $this->foundIn[$name] = $path;
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
                $this->foundIn[$name] = $path;
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
