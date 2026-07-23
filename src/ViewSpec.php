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
 * A specification for building a template registry: the explicit name-to-file
 * map, the search paths, the namespaced search paths, and the file extension.
 *
 * These four things describe one registry. Passing them to
 * _ViewFactory::newInstance()_ as flat arguments would interleave the view
 * registry's four with the layout registry's four, so they are grouped here
 * instead. Construct one with named arguments and pass it whole:
 *
 *     $spec = new ViewSpec(
 *         map: ['browse' => '/path/to/views/browse.php'],
 *         paths: ['/path/to/views'],
 *         namespaces: ['blog' => ['/path/to/blog/templates']],
 *     );
 *
 * It is readonly and has no setters, which keeps the package's one rule intact:
 * setters return `void`. There is nothing here to mutate.
 *
 * @package Aura.View
 *
 */
readonly class ViewSpec
{
    /**
     *
     * Constructor.
     *
     * @param array<string, string|callable> $map A map of explicit template
     * names to file paths or callables.
     *
     * @param list<string> $paths Filesystem paths to search for templates.
     *
     * @param array<string, list<string>> $namespaces A map of namespaces to the
     * filesystem paths to search for that namespace's templates.
     *
     * @param string $extension The file extension to append to template names
     * when searching the paths.
     *
     */
    public function __construct(
        public array $map = [],
        public array $paths = [],
        public array $namespaces = [],
        public string $extension = '.php',
    ) {
    }

    /**
     *
     * Returns a new TemplateRegistry built from this specification.
     *
     */
    public function newRegistry(): TemplateRegistry
    {
        $registry = new TemplateRegistry(
            $this->map,
            $this->paths,
            $this->namespaces
        );
        $registry->setTemplateFileExtension($this->extension);

        return $registry;
    }
}
