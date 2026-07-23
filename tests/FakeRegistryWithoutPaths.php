<?php
declare(strict_types=1);

namespace Aura\View;

/**
 * A registry that satisfies TemplateRegistryInterface only -- the shape a
 * precompiled name-to-file map would have. parent() must degrade gracefully
 * against it rather than assuming every registry can resume a search.
 */
class FakeRegistryWithoutPaths implements TemplateRegistryInterface
{
    /** @var array<string, \Closure> */
    protected array $map = [];

    public function set(string $name, string|callable $spec): void
    {
        if (is_string($spec)) {
            $this->map[$name] = function (array $__VARS__ = []) use ($spec): void {
                extract($__VARS__, EXTR_SKIP);
                require $spec;
            };
            return;
        }

        $this->map[$name] = $spec instanceof \Closure ? $spec : $spec(...);
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    public function get(string $name): \Closure
    {
        if (! $this->has($name)) {
            throw new Exception\TemplateNotFound($name);
        }

        return $this->map[$name];
    }
}
