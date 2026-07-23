## Escaping Output

**Aura.View ships no escaper, and that is deliberate -- not an omission to be fixed by adding a dependency.**

This package is not specific to any particular media type. HTML templates need HTML escaping, CSS templates need CSS escaping, XML templates need XML escaping, PDF templates need PDF escaping, RTF templates need RTF escaping, and so on. Bundling an *HTML* escaper would quietly narrow the package to one media type. Escaping therefore stays **explicit and yours to choose**.

That has a consequence you must act on: **every value you interpolate into a template is unescaped until you escape it.** All examples in this documentation escape manually for exactly that reason.

There is no auto-escaping, and there will not be. Auto-escaping requires a template compiler that knows the syntactic context of every interpolation; Aura.View executes plain PHP files and has no compilation step at all.

### Escaping With PHP Itself

For HTML, `htmlspecialchars()` with `ENT_QUOTES` and an explicit encoding is the baseline:

```php
<?php
$name = htmlspecialchars($this->name, ENT_QUOTES, 'UTF-8');
echo "Hello {$name}!";
?>
```

This is adequate for text between tags. It is **not** adequate for values placed into unquoted attributes, inside `<style>` blocks, or inside `<script>` blocks -- each of those is a different escaping context with different rules.

### Escaping With Aura.Html

[Aura.Html](https://github.com/auraphp/Aura.Html#escaping) provides escapers for four contexts -- `html`, `attr`, `css`, and `js` -- along with a large set of helpers. It is listed under `suggest`; install it if you want it:

```
composer require aura/html
```

The escaper is available statically once a _HelperLocatorFactory_ has been constructed:

```php
<?php
use Aura\Html\Escaper as e;

foreach ($this->items as $item) {
    echo "Item ID #" . e::h($item['id']) . " is '" . e::h($item['name']) . "'." . PHP_EOL;
}
?>
```

The static methods are `e::h()` for HTML, `e::a()` for attributes, `e::c()` for CSS, and `e::j()` for JavaScript.

Aura.Html's helpers escape their own output, so reaching for them via the [helper manager](helpers.md) covers most cases without a separate escaping call:

```php
<?php
// the value is escaped by the helper
echo $this->input([
    'type'  => 'text',
    'name'  => 'name',
    'value' => $this->name,
]);
?>
```

### Known Gap

Aura.Html's escaper has no URL-context method. A value interpolated into an `href` or `src` needs URL escaping, which none of `h`, `a`, `c`, or `j` provides. Until that lands, escape URL components at the call site.
