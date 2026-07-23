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
 * The minimum contract for a template registry: name in, closure out.
 *
 * Search-path management is deliberately *not* part of this interface; a
 * registry backed by a compiled name-to-file map has no paths to manage. See
 * SearchPathInterface for that half.
 *
 * @package Aura.View
 *
 */
interface TemplateRegistryInterface
{
    /**
     *
     * Registers a template.
     *
     * If the spec is a string, it is treated as a path to a PHP include file
     * and is wrapped in a closure that includes that file. Otherwise the spec
     * is treated as a callable and is normalized to a \Closure.
     *
     */
    public function set(string $name, string|callable $spec): void;

    /**
     *
     * Is a named template registered (or findable)?
     *
     */
    public function has(string $name): bool;

    /**
     *
     * Gets a template from the registry.
     *
     * @throws Exception\TemplateNotFound when the name cannot be resolved.
     *
     */
    public function get(string $name): \Closure;
}
