## Interfaces

Version 6.0 splits what used to be one concrete class into two interfaces, so that a framework can substitute a registry without extending _TemplateRegistry_. In 2.x, `setTemplateRegistry()` type-hinted the concrete class, which made substitution impossible without inheritance.

### TemplateRegistryInterface

The minimum contract: name in, closure out.

```php
interface TemplateRegistryInterface
{
    public function set(string $name, string|callable $spec): void;
    public function has(string $name): bool;
    public function get(string $name): \Closure;
}
```

A string `$spec` is treated as a path to a PHP include file and is wrapped in a closure. Any other callable is normalised to a `\Closure`, so that `get()` can always return something the _View_ can bind `$this` to.

`get()` throws _Aura\View\Exception\TemplateNotFound_ when the name cannot be resolved.

The _View_ constructor and the `getViewRegistry()` / `getLayoutRegistry()` methods are typed against this interface.

### SearchPathInterface

Filesystem search-path management, kept deliberately separate:

```php
interface SearchPathInterface
{
    public function getPaths(): array;
    public function setPaths(array $paths): void;
    public function prependPath(string $path, ?string $namespace = null): void;
    public function appendPath(string $path, ?string $namespace = null): void;
    public function setNamespaces(array $namespaces): void;
    public function hasNamespace(string $namespace): bool;
    public function getNamespaces(): array;
    public function getNamespacePaths(string $namespace): array;
    public function getResolvedPath(string $name): ?string;
    public function getNext(string $name, string $afterPath): ?ResolvedTemplate;
    public function setTemplateFileExtension(string $templateFileExtension): void;
}
```

`getResolvedPath()` reports which directory actually satisfied a name (null for a mapped or unresolvable one) -- the answer to "which package's template won?".

`getNext()` resumes the search after a given directory, which is what makes a shadowed template reachable; it backs [`parent()`](templates.md#extending-a-shadowed-template). It returns a _ResolvedTemplate_ -- a readonly `name` / `template` / `path` triple -- rather than a bare _\Closure_, because walking a chain more than one step needs the path the parent itself came from, and a closure does not carry that.

`getNamespaces()` returns the whole namespace-to-paths map, and
`getNamespacePaths()` returns the search paths for one namespace (an empty
array if that namespace is not registered). They answer "which directory did
this template come from?" when several modules contribute paths under the same
namespace -- previously only the un-namespaced paths were readable back out.

Path management is **not** part of _TemplateRegistryInterface_ because a registry backed by a precompiled name-to-file map has no paths to manage -- it resolves names from a lookup table built ahead of time. Forcing such a registry to implement `prependPath()` would mean stubbing out methods it cannot honour.

_TemplateRegistry_, the implementation shipped with this package, implements both interfaces. So if you are using the default registry, everything continues to work exactly as before. The split only matters when you write or accept an alternative implementation:

```php
<?php
// accept any registry
public function setViewRegistry(TemplateRegistryInterface $registry): void

// accept only a registry you can contribute paths to
public function addTemplatePath(SearchPathInterface $registry, string $path): void
```

### HelperRegistryInterface

```php
interface HelperRegistryInterface
{
    public function set(string $name, callable $callable, bool $override = false): void;
    public function has(string $name): bool;
    public function get(string $name): callable;
}
```

`set()` throws _Aura\View\Exception\HelperAlreadyRegistered_ when the name is taken; see [Helper Name Collisions](helpers.md#helper-name-collisions).

Note that the _View_ does **not** type-hint against this interface -- the helper manager parameter is `?object`. See [Custom Helper Managers](helpers.md#custom-helper-managers) for why.

### Why These Live In aura/view

The suite has a precedent for separate interface packages (Aura.Filter_Interface, Aura.Session_Interface), but these three interfaces are small enough to live in `aura/view` itself. There is no `Aura.View_Interface` package, and there will not be one unless a second implementation lands that needs to be shared across packages.
