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

A name containing more than one `::` is invalid and raises `\InvalidArgumentException`.

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

Alternatively you can pass a [helper manager](helpers.md), plus mapping information or paths for views and layouts, directly to the _ViewFactory_:

```php
<?php
$view_factory = new \Aura\View\ViewFactory;
$view = $view_factory->newInstance(
    null, // the helper manager; null gives you a HelperRegistry
    [
        'browse' => '/path/to/views/browse.php',
    ],
    [
        '/path/to/views/welcome',
        '/path/to/views/user',
    ],
    [
        'layout' => '/path/to/layouts/default.php',
    ],
    [
        '/path/to/layouts',
    ],
);
?>
```

If you pass the mapping information or paths for views and layouts this way, you do not need to call `getViewRegistry()` or `getLayoutRegistry()` and `set()` the mapping information afterwards. That is, the above is equivalent to:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('browse', '/path/to/views/browse.php');

$layout_registry = $view->getLayoutRegistry();
$layout_registry->set('layout', '/path/to/layouts/default.php');
?>
```

> N.b.: Namespaced paths are not reachable through the factory; set them on the
> registries with `appendPath()` or `setNamespaces()`.
