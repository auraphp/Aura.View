<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
declare(strict_types=1);

namespace Aura\View;

/**
 *
 * The contract for a registry of named helper callables.
 *
 * Note that the _View_ does **not** type-hint against this interface. A view
 * reaches its helper manager only through `__call()`, so anything with a
 * `__call()` method -- including Aura.Html's _HelperLocator_ -- is a valid
 * helper manager. This interface describes the registry contract itself, for
 * code that builds or substitutes registries rather than merely calling them.
 *
 * @package Aura.View
 *
 */
interface HelperRegistryInterface
{
    /**
     *
     * Registers a helper under a name.
     *
     */
    public function set(string $name, callable $callable): void;

    /**
     *
     * Is a named helper registered?
     *
     */
    public function has(string $name): bool;

    /**
     *
     * Gets a helper from the registry.
     *
     * @throws Exception\HelperNotFound when the name is not registered.
     *
     */
    public function get(string $name): callable;
}
