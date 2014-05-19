# Aura View

This package provides an implementation of the [TemplateView](http://martinfowler.com/eaaCatalog/templateView.html) and 
[TwoStepView](http://martinfowler.com/eaaCatalog/twoStepView.html) patterns, with support for helpers and for closures as templates, using PHP itself as the templating language. Template code can be in closures or PHP include files.

It is preceded by systems such as
[`Savant`](http://phpsavant.com),
[`Zend_View`](http://framework.zend.com/manual/en/zend.view.html), and
[`Solar_View`](http://solarphp.com/class/Solar_View).

## Foreword

### Installation

This library requires PHP 5.4 or later, and has no userland dependencies.

It is installable and autoloadable via Composer as [aura/view](https://packagist.org/packages/aura/view).

Alternatively, [download a release](https://github.com/auraphp/Aura.View/releases) or clone this repository, then require or include its _autoload.php_ file.

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/auraphp/Aura.View/badges/quality-score.png?s=82e59f35f779d7f5a2bdb87dea1d1842909674ef)](https://scrutinizer-ci.com/g/auraphp/Aura.View/)
[![Code Coverage](https://scrutinizer-ci.com/g/auraphp/Aura.View/badges/coverage.png?s=776e6c29a00984aea422f416fd90108a5f88ca87)](https://scrutinizer-ci.com/g/auraphp/Aura.View/)
[![Build Status](https://travis-ci.org/auraphp/Aura.View.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.View)

To run the [PHPUnit][] tests at the command line, go to the _tests_ directory and issue `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PHPUnit]: http://phpunit.de/manual/
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.


## Getting Started

### Instantiation

To instantiate a _View_ object, use the _ViewFactory_:

```php
<?php
$view_factory = new \Aura\View\ViewFactory;
$view = $view_factory->newInstance();
?>
```

### Escaping Output

Security-minded observers will note that all the examples in this document use unescaped output. Because this package is not specific to any particular media type, it **does not** come with escaping functionality.

When you generate output via templates, you **must** escape it appropriately for security purposes. This means that HTML templates should use HTML escaping, CSS templates should use CSS escaping, XML templates should use XML escaping, PDF templates should use PDF escaping, RTF templates should use RTF escaping, and so on.

For a good set of HTML escapers, please consider [Aura.Html](https://github.com/auraphp/Aura.Html#escaping).

### Registering View Templates

Now that we have a _View_, we need to add named templates to its view template registry. These can be closures or PHP file paths.  For example:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('browse', function () {
    echo "The browse view.";
});
$view_registry->set('read', '/path/to/views/read.php');
?>
```

Note that we use `echo`, and not `return`, in closures. Likewise, the PHP file should use `echo` to generate output.

> N.b.: The template logic will be executed inside the _View_ object scope,
> which means that `$this` in the template code will refer to the _View_
> object. This is true both for closures and for PHP files.

### Rendering A One-Step View

Now that we have registered some templates, we tell the _View_ which one we want to use, and then invoke the _View_:

```php
<?php
$view->setView('browse');
$output = $view();
?>
```

The `$output` in this case will be "The browse view."

### Setting Data

We will almost always want to use dynamic data in our templates. To assign a data collection to the _View_, use the `setData()` method and either an array or an object. We can then use data elements as if they are properties on the 
_View_ object:

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('hello', function () {
    echo "Hello {$this->name}!";
});
$view->setData(array('name' => 'World'));
$view->setView('hello');
$output = $view();
?>
```

> N.b.: Recall that `$this` in the template logic refers to the _View_ object,
> so that data assigned to the _View_ can be accessed as properties on `$this`.

The `$output` in this case will be "Hello World!".

### Using Sub-Templates (aka "Partials")

Sometimes we will want to split a template up into multiple pieces. We can
render these "partial" template pieces using the `render()` method in our main template code. 

First, we place the sub-template in the view registry (or in the layout regsitry if it for use in layouts). Then we `render()` it from inside the main template code. Sub-templates can use any naming scheme we like. Some systems use the convention of prefixing partial templates with an underscore, and the following example will use that convention.

Because `$this` is available in both the main template and sub-template scopes, we need to deconflict any variables specifically for sub-templates with variables intended for the main templates. The following example does so by prefixing sub-template variables with an underscore, but you can choose any convention you like.

```php
<?php
// add templates to the view registry
$view_registry = $view->getViewRegistry();

// the "main" template
$view_registry->set('item_rows', function () {
    foreach ($this->items as $this->_item) {
        echo $this->render('_item_row');
    };
});

// the "sub" (partial) template
$view_registry->set('_item_row', function () {
    echo "The item names '{$this->_item['name']}'' "
       . "costs {$this->_item['price']}" . PHP_EOL
});

// set the data and the view template name
$view->setData(array('items' => array(...));
$view->setView('item_rows');

// execute the view
$output = $view();
?>
```

Alternatively, we can use `include` or `require` to execute a PHP file directly
in the current template scope.

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

This library does not come with any view helpers. You will need to add your own
helpers to the registry as closures or invokable objects.

### Custom Helper Managers

The _View_ is not type-hinted to any particular class for its helper manager. This means you may inject an arbitrary object of your own at _View_ construction time to manage helpers. To do so, pass a helper manager of your own to the _ViewFactory_.

```php
<?php
class OtherHelperManager
{
    public function __call($helper_name, $args)
    {
        // logic to call $helper_name with
        // $args and return the result
    }
}

$helpers = new OtherHelperManager;
$view = $view_factory->newInstance($helpers);
?>
```

For a comprehensive set of HTML helpers, including form and input helpers, please consider the [Aura.Html](https://github.com/Aura.Html) package and its _HelperLocator_ as an alternative to the _HelperRegistry_ in this package. You can pass it to the _ViewFactory_ like so:

```php
<?php
$helpers_factory = new Aura\Html\HelperLocatorFactory;
$helpers = $helpers_factory->newInstance();
$view = $view_factory->newInstance($helpers);
?>
```

### Rendering a Two-Step View

To wrap the main content in a layout as part of a two-step view, we register
layout templates with the _View_ and then call `setLayout()` to pick one of
them for the second step. (If no layout is set, the second step will not be
executed.)

```php
<?php
$view_registry = $view->getViewRegistry();
$view_registry->set('main', function () {
    echo "This is the main content." . PHP_EOL;
});

$layout_registry = $view->getLayoutRegistry();
$layout_registry->set('wrapper', function () {
    echo "Before the main content." . PHP_EOL;
    echo $this->getContent();
    echo "After the main content." . PHP_EOL;
})

$view->setView('main_content');
$view->setLayout('wrapper');
$output = $view();
?>
```

The output from the view template is automatically retained and becomes available via the `getContent()` method. We can also call `setLayout()` from inside the view template, allowing us to pick a layout as part of the view logic.

All template data is shared between the view and the layout. Any data values
assigned to the view, or modified by the view, are used as-is by the layout.
Similarly, all helpers are shared between the view and the layout. This sharing
situation allows the view to modify data and helpers before the layout is 
executed.
