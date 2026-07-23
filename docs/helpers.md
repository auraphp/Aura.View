## Helpers

### Using Helpers

The _ViewFactory_ instantiates the _View_ with an empty _HelperRegistry_ to manage helpers. We can register closures or other invokable objects as helpers through the _HelperRegistry_. We can then call these helpers as if they are methods on the _View_.

```php
<?php
$helpers = $view->getHelpers();
$helpers->set('hello', function ($name) {
    return "Hello {$name}!";
});

$view_registry = $view->getViewRegistry();
$view_registry->set('index', function () {
    echo $this->hello('World');
});

$view->setView('index');
$output = $view();
?>
```

This library does not come with any view helpers. You will need to add your own helpers to the registry as closures or invokable objects, or use a helper manager that brings its own -- see [Using Aura.Html Helpers](#using-aurahtml-helpers) below.

Helpers return their output rather than echoing it, and they are responsible for their own escaping; Aura.View does not escape helper output for you. See [Escaping Output](escaping.md).

If a called helper does not resolve, the _View_ throws _Aura\View\Exception\HelperNotFound_.

### Custom Helper Managers

The helper manager parameter is typed `?object` -- **not** `HelperRegistryInterface` -- and that is on purpose. A _View_ reaches its helpers through exactly one call:

```php
public function __call(string $name, array $args): mixed
```

So the only real requirement on a helper manager is that it answer `__call()`. Typing the parameter to `HelperRegistryInterface` would exclude perfectly good managers -- including Aura.Html's _HelperLocator_, which cannot implement an Aura.View interface without depending on Aura.View, the wrong direction -- while using none of that interface's three methods.

Inject any object you like at _View_ construction time:

```php
<?php
class OtherHelperManager
{
    public function __call(string $helper_name, array $args): mixed
    {
        // logic to call $helper_name with
        // $args and return the result
    }
}

$helpers = new OtherHelperManager;
$view = $view_factory->newInstance($helpers);
?>
```

An object with real methods works just as well as one with `__call()`; the _View_ only needs the call to resolve.

### Using Aura.Html Helpers

For a comprehensive set of HTML helpers, including form and input helpers, consider [Aura.Html](https://github.com/auraphp/Aura.Html) and its _HelperLocator_ as an alternative to the _HelperRegistry_ in this package. **No adapter is needed**; pass it straight to the _ViewFactory_:

```php
<?php
$helpers_factory = new \Aura\Html\HelperLocatorFactory;
$helpers = $helpers_factory->newInstance();
$view = $view_factory->newInstance($helpers);

$view_registry = $view->getViewRegistry();
$view_registry->set('form', function () {
    echo $this->input([
        'type'  => 'text',
        'name'  => 'name',
        'value' => $this->name,
    ]);
});
?>
```

Aura.Html is an optional `suggest`. Aura.View does not require it, and takes no runtime dependency on it.

### The HelperRegistryInterface

_HelperRegistryInterface_ describes the registry contract itself -- for code that *builds or substitutes* registries rather than merely calling them:

```php
interface HelperRegistryInterface
{
    public function set(string $name, callable $callable): void;
    public function has(string $name): bool;
    public function get(string $name): callable;
}
```

_HelperRegistry_ implements it. The _View_ does not depend on it; see [Interfaces](interfaces.md).
