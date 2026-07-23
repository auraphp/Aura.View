## Templates

### Closures As Templates

The view and layout registries accept closures as templates. For example, these are closure-based equivalents of the `browse.php` and `_item.php` template files from [Getting Started](getting-started.md):

```php
<?php
$view_registry->set('browse', function () {
    foreach ($this->items as $item) {
        echo $this->render('_item', [
            'item' => $item,
        ]);
    }
});

$view_registry->set('_item', function (array $vars) {
    extract($vars, EXTR_SKIP);
    $id = htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
    echo "Item ID #{$id} is '{$name}'." . PHP_EOL;
});
?>
```

When registering a closure-based template, continue to use `echo` instead of `return` when generating output. The closure is rebound to the _View_ object, so `$this` in the closure will refer to the _View_ just as it does in a file-based template.

A bit of extra effort is required with closure-based sub-templates (aka "partials"). Whereas file-based templates automatically extract the passed array of variables into the local scope, a closure-based template must:

1. Define a function parameter to receive the injected variables (the `$vars` param in the `_item` template); and,

2. Extract the injected variables using `extract()`. Alternatively, the closure may use the injected variables parameter directly.

Aside from that, closure-based templates work exactly like file-based templates.

### Other Callables As Templates

Any callable is accepted, not only closures -- an array callable or an invokable object works too. The registry normalises whatever you give it to a `\Closure`, so that the _View_ can bind `$this` to it:

```php
<?php
$view_registry->set('browse', [$someObject, 'renderBrowse']);
$view_registry->set('_item', new ItemTemplate);
?>
```

Because the callable is rebound to the _View_, `$this` inside it refers to the _View_ and **not** to the original object. If a template needs state from its own object, capture it in a closure's `use` clause instead.

> N.b.: A string is always treated as a file path, never as a callable. This
> means `$view_registry->set('foo', 'strtoupper')` registers a template file
> named `strtoupper`, not the `strtoupper()` function.

### Registering Template Search Paths

We can also tell the view and layout registries to search the filesystem for templates. First, we tell the registry what directories contain template files:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->setPaths([
    '/path/to/foo',
    '/path/to/bar',
    '/path/to/baz',
]);
?>
```

When we refer to named templates later, the registry will search from the first directory to the last. For finer control over the search paths, we can call `prependPath()` to add a directory to search earlier, or `appendPath()` to add a directory to search later. Regardless, the _View_ will auto-append `.php` to the end of template names when searching through the directories.

Explicitly mapped templates always take precedence over the search paths.

#### Template Namespaces

We can also add namespaced templates which we can refer to with the syntax `namespace::template`. We can add directories that correspond to namespaces:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->appendPath('/path/to/templates', 'my-namespace');

$view->setView('my-namespace::browse');
?>
```

When we refer to namespaced templates, only the paths associated with that namespace will be searched.

A name containing more than one `::` is invalid and raises `Aura\View\Exception\InvalidTemplateName`.

To read the namespaced paths back out -- useful when several modules contribute to the same namespace and you need to know which directory a template came from -- use `getNamespaces()` for the whole map, or `getNamespacePaths()` for one namespace:

```php
<?php
$view_registry->getNamespaces();
// ['my-namespace' => ['/path/to/templates']]

$view_registry->getNamespacePaths('my-namespace');
// ['/path/to/templates']

$view_registry->getNamespacePaths('no-such-namespace');
// []
?>
```

These are the namespaced counterparts of `getPaths()`; `hasNamespace()` reports whether a namespace is registered at all.

### Extending A Shadowed Template

Search paths are first-hit-wins, so a template in an earlier directory *shadows* one of the same name in a later directory. On its own that means a template can only be replaced wholesale: to change one part of a package's template, you copy the whole file into your own directory and edit it -- and it stops tracking the original from then on.

`parent()` renders the template the current one shadows, by resuming the search *after* the directory the current template was found in:

```php
<?php
$view_registry->setPaths([
    '/app/templates',           // searched first
    '/vendor/acme/templates',   // shadowed by /app
]);
?>
```

```php
<?php /* /vendor/acme/templates/read.php */ ?>
<h1><?= $this->title ?></h1>
<?= $this->getSection('extra') ?>
```

```php
<?php /* /app/templates/read.php -- shadows the one above */ ?>
<?php $this->beginSection('extra') ?>
    <p>Something only this application wants.</p>
<?php $this->endSection() ?>
<?= $this->parent() ?>
```

Rendering `read` now runs the application's file, which sets a section and then renders the package's file, which picks that section up. Only the difference lives in the application.

Chains can be any depth -- an application shadowing a module shadowing a core default -- and each level calls `parent()` to reach the next. You can pass variables down:

```php
<?= $this->parent(['heading' => 'Custom']) ?>
```

`parent()` returns `''` rather than throwing when there is nothing further to render:

- the current template shadows nothing (common while developing -- you add an override before the thing it overrides exists, or the name is simply unique);
- the template came from the explicit map, which has no search path behind it;
- the registry does not implement _SearchPathInterface_ at all.

Those three are the answers to a well-formed question: there *is* a current template, and nothing follows it.

#### Strict Parent Mode

That forgiveness has a cost: a search path with a typo, a path registered in the wrong order, or a registry that turns out to have no paths at all produces exactly the same `''`. Overrides quietly stop composing and start replacing -- the page renders, nothing is raised, and the only symptom is missing markup.

Turn the three cases into _Aura\View\Exception\ParentNotFound_ while developing:

```php
<?php
$view->setStrictParent(true);
?>
```

The message names the template and why the lookup came up empty:

```
parent() found no template to render for 'read': nothing after
'/app/templates' in the search paths has that name.
```

Leave it **off in production**, where the forgiving behaviour is what you want -- an override written before the template it overrides exists should not take a page down.

It takes a bool rather than reading an environment variable on its own. Aura.View has no config layer and no dependencies, so deciding what "development" means belongs to whatever wires the _View_ up:

```php
<?php
$view->setStrictParent((bool) getenv('APP_DEBUG'));
?>
```

Calling `parent()` when there is no current template at all is a different thing, and throws _Aura\View\Exception_ regardless of strict mode. Template code cannot reach it: if a template is running then it was rendered, and a render always has a frame. It is a guard for code that steps outside the normal path -- most usefully, a _View_ subclass that overrides `render()` without maintaining the render stack, which would otherwise make every `parent()` in the application quietly return `''`. If you override `render()`, push a frame before invoking the template and pop it in a `finally`; see `Aura\View\View::render()`.

Because resolution is per *name*, `parent()` is the seam a modular application needs: the shadowing file and the shadowed file share a name, and neither has to know how many other packages sit in the chain.

### Changing The Template File Extension

By default, each _TemplateRegistry_ will auto-append `.php` to template file names. If the template files end with a different extension, change it using the `setTemplateFileExtension()` method:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->setTemplateFileExtension('.phtml');
?>
```

The _TemplateRegistry_ instance used for the layouts is separate from the one for the views, so it may be necessary to change the template file extension on it as well:

```php
<?php
$layout_registry = $view->getLayoutRegistry();
$layout_registry->setTemplateFileExtension('.phtml');
?>
```

### Configuring the Registries Up Front

Rather than configuring each registry after the fact, you can describe both up front and pass them to the _ViewFactory_. A _ViewSpec_ describes one registry -- its map, paths, namespaces, and file extension:

```php
<?php
use Aura\View\ViewFactory;
use Aura\View\ViewSpec;

$view_factory = new ViewFactory;

$view = $view_factory->newInstance(
    view: new ViewSpec(
        map: ['browse' => '/path/to/views/browse.php'],
        paths: ['/path/to/views/welcome', '/path/to/views/user'],
        namespaces: ['blog' => ['/path/to/blog/templates']],
    ),
    layout: new ViewSpec(
        map: ['default' => '/path/to/layouts/default.php'],
        paths: ['/path/to/layouts'],
    ),
);
?>
```

`newInstance()` takes three parameters, all optional:

1. `$helpers` -- the [helper manager](helpers.md); omit it, or pass `null`, to get the default _HelperRegistry_
2. `$view` -- a _ViewSpec_ for the view registry
3. `$layout` -- a _ViewSpec_ for the layout registry

`$helpers` comes first so that `newInstance($helpers)` -- the one-line [Aura.Html wiring](helpers.md#using-aurahtml-helpers) -- keeps working as a positional call.

The example above is equivalent to configuring the registries by hand:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('browse', '/path/to/views/browse.php');
$view_registry->setPaths(['/path/to/views/welcome', '/path/to/views/user']);
$view_registry->appendPath('/path/to/blog/templates', 'blog');

$layout_registry = $view->getLayoutRegistry();
$layout_registry->set('default', '/path/to/layouts/default.php');
$layout_registry->setPaths(['/path/to/layouts']);
?>
```

#### The ViewSpec

Every _ViewSpec_ parameter is optional, so pass only what you need:

```php
<?php
use Aura\View\ViewSpec;

new ViewSpec(
    map: [],          // array<string, string|callable>
    paths: [],        // list<string>
    namespaces: [],   // array<string, list<string>>
    extension: '.php' // string
);
?>
```

The `extension` corresponds to `setTemplateFileExtension()`, and the view and layout registries are independent -- so a project with `.phtml` views and `.php` layouts is a single call:

```php
<?php
use Aura\View\ViewSpec;

$view = $view_factory->newInstance(
    view: new ViewSpec(paths: ['/path/to/views'], extension: '.phtml'),
    layout: new ViewSpec(paths: ['/path/to/layouts']),
);
?>
```

A _ViewSpec_ is a readonly value object with no setters. Build it once, with named arguments; there is nothing to mutate afterwards. If you need to vary one, construct a new one.

It can also build a registry on its own, which is useful if you are assembling a _View_ without the factory:

```php
<?php
use Aura\View\ViewSpec;

$registry = (new ViewSpec(paths: ['/path/to/views']))->newRegistry();
?>
```
