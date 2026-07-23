## Getting Started

### Instantiation

To instantiate a _View_ object, use the _ViewFactory_:

```php
<?php
$view_factory = new \Aura\View\ViewFactory;
$view = $view_factory->newInstance();
?>
```

> N.b.: A _View_ is request-scoped. Invoking it mutates the instance -- it holds
> the rendered content, the section bodies, and the template data. Build a new
> one per request rather than sharing a single instance, particularly under a
> long-lived worker such as Swoole, RoadRunner, or FrankenPHP.

### Registering View Templates

Now that we have a _View_, we need to add named templates to its view template registry. These are typically PHP file paths, but [templates can also be closures](templates.md#closures-as-templates). For example:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('browse', '/path/to/views/browse.php');
?>
```

The `browse.php` file may look something like this:

```php
<?php
foreach ($this->items as $item) {
    $id = htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
    echo "Item ID #{$id} is '{$name}'." . PHP_EOL;
}
?>
```

Note that we use `echo`, and not `return`, in templates.

Note also that the values are escaped by hand. Aura.View ships no escaper, by design; see [Escaping Output](escaping.md).

> N.b.: The template logic will be executed inside the _View_ object scope,
> which means that `$this` in the template code will refer to the _View_
> object. The same is true for closure-based templates.

### Setting Data

We will almost always want to use dynamic data in our templates. To assign a data collection to the _View_, use the `setData()` method and either an array or an object. We can then use data elements as if they are properties on the _View_ object.

```php
<?php
$view->setData([
    'items' => [
        [
            'id' => '1',
            'name' => 'Foo',
        ],
        [
            'id' => '2',
            'name' => 'Bar',
        ],
        [
            'id' => '3',
            'name' => 'Baz',
        ],
    ],
]);
?>
```

> N.b.: Recall that `$this` in the template logic refers to the _View_ object,
> so that data assigned to the _View_ can be accessed as properties on `$this`.

The `setData()` method will overwrite all existing data in the _View_ object. The `addData()` method, on the other hand, will merge with existing data in the _View_ object.

### Invoking A One-Step View

Now that we have registered a template and assigned some data to the _View_, we tell the _View_ which template to use, and then invoke the _View_:

```php
<?php
$view->setView('browse');
$output = $view->__invoke(); // or just $view()
?>
```

The `$output` in this case will be something like this:

```
Item #1 is 'Foo'.
Item #2 is 'Bar'.
Item #3 is 'Baz'.
```

### Using Sub-Templates (aka "Partials")

Sometimes we will want to split a template up into multiple pieces. We can render these "partial" template pieces using the `render()` method in our main template code.

First, we place the sub-template in the view registry (or in the layout registry if it is for use in layouts). Then we `render()` it from inside the main template code. Sub-templates can use any naming scheme we like. Some systems use the convention of prefixing partial templates with an underscore, and the following example will use that convention.

Second, we can pass an array of variables to be extracted into the local scope of the partial template. (The `$this` variable will always be available regardless.)

For example, let's split up our `browse.php` template file so that it uses a sub-template for displaying items.

```php
<?php
// add templates to the view registry
$view_registry = $view->getViewRegistry();

// the "main" template
$view_registry->set('browse', '/path/to/views/browse.php');

// the "sub" template
$view_registry->set('_item', '/path/to/views/_item.php');
?>
```

We extract the item-display code from `browse.php` into `_item.php`:

```php
<?php
$id = htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8');
$name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
echo "Item ID #{$id} is '{$name}'." . PHP_EOL;
?>
```

Then we modify `browse.php` to use the sub-template:

```php
<?php
foreach ($this->items as $item) {
    echo $this->render('_item', [
        'item' => $item,
    ]);
}
?>
```

The output will be the same as earlier when we invoke the view.

> N.b.: Alternatively, we can use `include` or `require` to execute a PHP file
> directly in the current template scope.

### Using Sections

Sections are similar to sub-templates (aka "partials") except that they are captured inline for later use. In general, they are used by view templates to capture output for layout templates.

For example, we can capture output in the view template to a named section ...

```php
<?php
// begin buffering output for a named section
$this->beginSection('local-nav');

echo "<div>";
// ... echo the local navigation output ...
echo "</div>";

// end buffering and capture the output
$this->endSection();
?>
```

... and then use that output in a layout template:

```php
<?php
if ($this->hasSection('local-nav')) {
    echo $this->getSection('local-nav');
} else {
    echo "<div>No local navigation.</div>";
}
?>
```

In addition, the `setSection()` method can be used to set the section body directly, instead of capturing it:

```php
<?php
$this->setSection('local-nav', $this->render('_local-nav'));
?>
```

Every `beginSection()` needs a matching `endSection()`; calling `endSection()` without one throws _Aura\View\Exception_.

### Rendering a Two-Step View

To wrap the main content in a layout as part of a two-step view, we register layout templates with the _View_ and then call `setLayout()` to pick one of them for the second step. (If no layout is set, the second step will not be executed.)

Let's say we have already set the `browse` template above into our view registry. We then set a layout template called `default` into the layout registry:

```php
<?php
$layout_registry = $view->getLayoutRegistry();
$layout_registry->set('default', '/path/to/layouts/default.php');
?>
```

The `default.php` layout template might look like this:

```html+php
<html>
<head>
    <title>My Site</title>
</head>
<body>
<?= $this->getContent(); ?>
</body>
</html>
```

We can then set the view and layout templates on the _View_ object and then invoke it:

```php
<?php
$view->setView('browse');
$view->setLayout('default');
$output = $view->__invoke(); // or just $view()
?>
```

The output from the inner view template is automatically retained and becomes available via the `getContent()` method on the _View_ object. The layout template then calls `getContent()` to place the inner view results in the outer layout template.

> N.b. We can also call `setLayout()` from inside the view template, allowing us
> to pick a layout as part of the view logic.

The view template and the layout template both execute inside the same _View_ object. This means:

- All data values are shared between the view and the layout. Any data assigned to the view, or modified by the view, is used as-is by the layout.

- All helpers are shared between the view and the layout. This sharing situation allows the view to modify data and helpers before the layout is executed.

- All section bodies are shared between the view and the layout. A section that is captured from the view template can therefore be used by the layout template.
