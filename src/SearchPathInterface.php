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
 * A template registry that resolves names by searching a list of filesystem
 * paths, optionally grouped under namespaces.
 *
 * This is separate from TemplateRegistryInterface so that a registry backed by
 * a precompiled name-to-file map can satisfy the registry contract without
 * pretending to have paths. Consumers that genuinely need to contribute paths
 * (a module cascade, for instance) should type against this interface.
 *
 * @package Aura.View
 *
 */
interface SearchPathInterface
{
    /**
     *
     * Gets a copy of the current search paths.
     *
     * @return list<string>
     *
     */
    public function getPaths(): array;

    /**
     *
     * Sets the search paths directly, replacing any existing paths.
     *
     * @param list<string> $paths
     *
     */
    public function setPaths(array $paths): void;

    /**
     *
     * Adds one path to the top of the search paths, so that it is searched
     * before the paths already registered.
     *
     */
    public function prependPath(string $path, ?string $namespace = null): void;

    /**
     *
     * Adds one path to the end of the search paths, so that it is searched
     * after the paths already registered.
     *
     */
    public function appendPath(string $path, ?string $namespace = null): void;

    /**
     *
     * Sets the namespaced search paths directly, replacing any existing ones.
     *
     * @param array<string, list<string>> $namespaces
     *
     */
    public function setNamespaces(array $namespaces): void;

    /**
     *
     * Is a namespace registered?
     *
     */
    public function hasNamespace(string $namespace): bool;

    /**
     *
     * Gets a copy of the namespaced search paths, keyed on namespace.
     *
     * @return array<string, list<string>>
     *
     */
    public function getNamespaces(): array;

    /**
     *
     * Gets a copy of the search paths for one namespace; an unregistered
     * namespace has no paths, so it returns an empty array.
     *
     * @return list<string>
     *
     */
    public function getNamespacePaths(string $namespace): array;

    /**
     *
     * Gets the search path directory a name resolved from; null when the name
     * came from an explicit map, or cannot be resolved.
     *
     */
    public function getResolvedPath(string $name): ?string;

    /**
     *
     * Gets the next template of this name, resuming the search after a given
     * directory; null when nothing further in the chain has this name.
     *
     */
    public function getNext(string $name, string $afterPath): ?ResolvedTemplate;

    /**
     *
     * Sets the file extension used when searching the paths for templates.
     *
     */
    public function setTemplateFileExtension(string $templateFileExtension): void;
}
