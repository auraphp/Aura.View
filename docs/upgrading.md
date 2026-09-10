## Upgrading from 2.x

**Most calling code needs nothing beyond the PHP version.** Version 7.0 adds native types throughout, and that breaks classes which *extend* Aura.View rather than code which *calls* it. Three changes do reach callers, so check those: the five-argument positional form of `ViewFactory::newInstance()` (step 7), invoking a view with no view template set (step 8), and catching _InvalidHelpersObject_ (step 4).

The version jumps 2.x to 7.x. There is no 3.x, 4.x, 5.x, or 6.x of this package.

Work through these in order.

### 1. Move to PHP 8.4

```
composer require aura/view:^7.0
```

Aura.View 7.x still has no runtime dependencies.

### 2. Add Types To Any Methods You Override

This is the one change that fatals on load rather than at runtime, so it is the one to look for first. If you extend an Aura.View class, every overridden method needs a signature compatible with its parent. Most commonly this is a _TemplateRegistry_ subclass overriding the search hooks:

```php
<?php
// 2.x
protected function isReadable($file)   { /* ... */ }
protected function enclose($__FILE__)  { /* ... */ }

// 7.x
protected function isReadable(string $file): bool       { /* ... */ }
protected function enclose(string $__FILE__): \Closure  { /* ... */ }
?>
```

Note that `enclose()` must now genuinely return a `\Closure`. A quick way to find every override you need to touch is to load your classes and let PHP report the incompatible signatures.

### 3. Array Callables And Invokable Objects Now Work

No action needed -- this is a fix, not a break.

In 2.x, `TemplateRegistry::get()` had no return type. Its docblock claimed `\Closure`, but it actually returned whatever `set()` had stored: a `\Closure` for file paths and closures, but a raw `array` or object for other callables. `AbstractView::getTemplate()` then called `bindTo()` on that value and fatalled.

In 7.0, `set()` normalises every non-string callable to a `\Closure`, so `get(): \Closure` is honest and all four documented spec types work. If you wrapped array callables yourself to work around this, you can stop.

### 4. Catch \TypeError, Not InvalidHelpersObject

The helper manager parameter is typed `?object`, so PHP rejects a non-object with a `\TypeError` before _Aura\View\Exception\InvalidHelpersObject_ could be thrown. That exception class is retained so existing `catch` blocks still resolve, but nothing raises it; it will be removed in 8.0.

### 5. Move DI Wiring Into Your Own Container Config

`config/Common.php` and the `extra.aura` block are gone, along with the `Aura\View\_Config\` PSR-4 entry. That config extended `Aura\Di\Config`, a class that no longer exists in Aura.Di 5.x, and encoded the dead v2 kernel discovery convention -- so it was already broken. Wire the _View_ in your framework's own module or container config.

### 6. Type Against SearchPathInterface Where You Take A Registry

`getViewRegistry()` and `getLayoutRegistry()` now return _TemplateRegistryInterface_ rather than the concrete _TemplateRegistry_. Runtime behaviour is unchanged, since _TemplateRegistry_ implements _SearchPathInterface_ too. But if you write a method that accepts a registry and then adds paths to it, type that parameter _SearchPathInterface_. See [Interfaces](interfaces.md).

### 7. Wrap ViewFactory Arguments In A ViewSpec

`ViewFactory::newInstance()` took five positional arguments in 2.x. It now takes three, with the per-registry settings grouped into a _ViewSpec_:

```php
<?php
use Aura\View\ViewSpec;

// 2.x
$view = $view_factory->newInstance(
    $helpers,
    $view_map,
    $view_paths,
    $layout_map,
    $layout_paths
);

// 7.x
$view = $view_factory->newInstance(
    $helpers,
    new ViewSpec(map: $view_map, paths: $view_paths),
    new ViewSpec(map: $layout_map, paths: $layout_paths),
);
?>
```

`$helpers` is still the first parameter, so `newInstance()` and `newInstance($helpers)` are unaffected -- only the longer positional form changes, and it fails with a `\TypeError` naming the parameter rather than misbehaving quietly.

The grouping also closes two gaps: `namespaces` and `extension` were unreachable through the factory in 2.x, because it built each _TemplateRegistry_ with only two of its three constructor arguments and never touched `setTemplateFileExtension()`. See [Configuring the Registries Up Front](templates.md#configuring-the-registries-up-front).

### 8. Set A View Template Before Invoking

Invoking a _View_ with no view template set now returns `''` (wrapped in the layout, if one is set) rather than raising _TemplateNotFound_ on a null template name. If you relied on that exception to catch a misconfiguration, check `getView()` yourself.

### Things That Are Not A Problem

- **Setter chaining.** 7.0 types setters `: void`, but 2.x setters returned `null`, so no chain ever worked. There is nothing to un-chain. Aura.View is a service, not a specification builder; fluency buys nothing when there is no spec to assemble.

- **The Aura.Html wiring.** `$view_factory->newInstance($helpers)` with a _HelperLocator_ works exactly as it did in 2.x, with no adapter. See [Using Aura.Html Helpers](helpers.md#using-aurahtml-helpers).

- **Templates themselves.** Plain PHP files and closures are unchanged, `$this` still binds to the _View_, sections work the same, and there is still no auto-escaping.

The full list of changes, with rationale, is in [CHANGELOG.md](https://github.com/auraphp/Aura.View/blob/7.x/CHANGELOG.md).
