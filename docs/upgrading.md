## Upgrading from 2.x

**If you only ever called Aura.View, you likely have nothing to change beyond the PHP version.** Version 6.0 adds native types throughout; that breaks classes which *extend* Aura.View, not code which *calls* it.

The version jumps 2.x to 6.x to keep step with the rest of the suite (Aura.Filter `6.x`, Aura.Auth `6.x`, Aura.Router `6.x`). There is no 3.x, 4.x, or 5.x.

Work through these in order.

### 1. Move to PHP 8.4

```
composer require aura/view:^6.0
```

Aura.View 6.x still has no runtime dependencies.

### 2. Add Types To Any Methods You Override

This is the one change that fatals on load rather than at runtime, so it is the one to look for first. If you extend an Aura.View class, every overridden method needs a signature compatible with its parent. Most commonly this is a _TemplateRegistry_ subclass overriding the search hooks:

```php
<?php
// 2.x
protected function isReadable($file)   { /* ... */ }
protected function enclose($__FILE__)  { /* ... */ }

// 6.x
protected function isReadable(string $file): bool       { /* ... */ }
protected function enclose(string $__FILE__): \Closure  { /* ... */ }
?>
```

Note that `enclose()` must now genuinely return a `\Closure`. A quick way to find every override you need to touch is to load your classes and let PHP report the incompatible signatures.

### 3. Array Callables And Invokable Objects Now Work

No action needed -- this is a fix, not a break.

In 2.x, `TemplateRegistry::get()` had no return type. Its docblock claimed `\Closure`, but it actually returned whatever `set()` had stored: a `\Closure` for file paths and closures, but a raw `array` or object for other callables. `AbstractView::getTemplate()` then called `bindTo()` on that value and fatalled.

In 6.0, `set()` normalises every non-string callable to a `\Closure`, so `get(): \Closure` is honest and all four documented spec types work. If you wrapped array callables yourself to work around this, you can stop.

### 4. Catch \TypeError, Not InvalidHelpersObject

The helper manager parameter is typed `?object`, so PHP rejects a non-object with a `\TypeError` before _Aura\View\Exception\InvalidHelpersObject_ could be thrown. That exception class is retained so existing `catch` blocks still resolve, but nothing raises it; it will be removed in 7.0.

### 5. Move DI Wiring Into Your Own Container Config

`config/Common.php` and the `extra.aura` block are gone, along with the `Aura\View\_Config\` PSR-4 entry. That config extended `Aura\Di\Config`, a class that no longer exists in Aura.Di 5.x, and encoded the dead v2 kernel discovery convention -- so it was already broken. Wire the _View_ in your framework's own module or container config.

### 6. Type Against SearchPathInterface Where You Take A Registry

`getViewRegistry()` and `getLayoutRegistry()` now return _TemplateRegistryInterface_ rather than the concrete _TemplateRegistry_. Runtime behaviour is unchanged, since _TemplateRegistry_ implements _SearchPathInterface_ too. But if you write a method that accepts a registry and then adds paths to it, type that parameter _SearchPathInterface_. See [Interfaces](interfaces.md).

### 7. Set A View Template Before Invoking

Invoking a _View_ with no view template set now returns `''` (wrapped in the layout, if one is set) rather than raising _TemplateNotFound_ on a null template name. If you relied on that exception to catch a misconfiguration, check `getView()` yourself.

### Things That Are Not A Problem

- **Setter chaining.** 6.0 types setters `: void`, but 2.x setters returned `null`, so no chain ever worked. There is nothing to un-chain. Aura.View is a service, not a specification builder; the suite's fluent interfaces (Aura.SqlQuery, Aura.Html, Aura.Router) are all builders.

- **The Aura.Html wiring.** `$view_factory->newInstance($helpers)` with a _HelperLocator_ works exactly as it did in 2.x, with no adapter. See [Using Aura.Html Helpers](helpers.md#using-aurahtml-helpers).

- **Templates themselves.** Plain PHP files and closures are unchanged, `$this` still binds to the _View_, sections work the same, and there is still no auto-escaping.

The full list of changes, with rationale, is in [CHANGELOG.md](https://github.com/auraphp/Aura.View/blob/6.x/CHANGELOG.md).
