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
 * A registry for custom helpers.
 *
 * @package Aura.View
 *
 */
class HelperRegistry implements HelperRegistryInterface
{
    /**
     *
     * The map of registered helpers.
     *
     * @var array<string, callable>
     *
     */
    protected array $map = [];

    /**
     *
     * Constructor.
     *
     * @param array<string, callable> $map A map of helpers.
     *
     */
    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    /**
     *
     * Magic call to invoke helpers as methods on this registry.
     *
     * @param string $name The registered helper name.
     *
     * @param array<int, mixed> $args Arguments to pass to the helper
     * invocation.
     *
     */
    public function __call(string $name, array $args): mixed
    {
        return call_user_func_array($this->get($name), $args);
    }

    /**
     *
     * Registers a helper.
     *
     * @param string $name Register the helper under this name.
     *
     * @param callable $callable The callable helper.
     *
     */
    public function set(string $name, callable $callable): void
    {
        $this->map[$name] = $callable;
    }

    /**
     *
     * Is a named helper registered?
     *
     */
    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    /**
     *
     * Gets a helper from the registry.
     *
     * @throws Exception\HelperNotFound when the name is not registered.
     *
     */
    public function get(string $name): callable
    {
        if (! $this->has($name)) {
            throw new Exception\HelperNotFound($name);
        }

        return $this->map[$name];
    }
}
