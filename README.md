Aura View
=========

The Aura View package is an implementation of the
[`TemplateView`](http://martinfowler.com/eaaCatalog/templateView.html) pattern,
with support for automatic escaping, path stacks, and helpers. It adheres to
the "use PHP for presentation logic" ideology, and is preceded by systems such
as [`Savant`](http://phpsavant.com),
[`Zend_View`](http://framework.zend.com/manual/en/zend.view.html), and
[`Solar_View`](http://solarphp.com/class/Solar_View).

This package is compliant with [PSR-0][], [PSR-1][], and [PSR-2][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

Basic Usage
===========

Instantiation
-------------

The easiest way to instantiate a new `Template` with all the associated
helpers is to include the `instance.php` script.

```php
<?php
$template = require '/path/to/Aura.View/scripts/instance.php';
```

Then use the `Template` object to `fetch()` the output of a template script.

```php
<?php
echo $template->fetch('/path/to/tpl.php');
```

Alternatively, we can add the `Aura.View` package to an autoloader, and
instantiate manually:

```php
<?php
use Aura\View\Template;
use Aura\View\EscaperFactory;
use Aura\View\TemplateFinder;
use Aura\View\HelperLocator;

$template = new Template(
    new EscaperFactory,
    new TemplateFinder,
    new HelperLocator
);
```

(Note that if we instantiate manually, we will need to configure the
`HelperLocator` manually to add helper services. See the "Helpers" section
near the end of this page for more information.)


Assigning Data
--------------

We can add data to the template script as properties ...

```php
<?php
// business logic
$template->var = 'World';
```

... or by using the `addData()` method:

```php
<?php
// business logic
$template->addData([
    'foo' => 'value of foo',
    'bar' => 'value of bar',
]);
```

We can then refer to the data as properties from within the template script
using `$this`:

```php
<?php
// template script
echo $this->var;
```

Finally, we can replace all the `Template` data values at once using
`setData()`.

```php
<?php
// business logic
// this will remove $var, $foo, and $bar from the template
$template->setData([
    'baz' => 'Value of baz',
    'dib' => 'Value of dib',
]);
```


Writing Template Scripts
------------------------

Aura View template scripts are written in plain PHP and do not require a new
markup language. The template scripts are executed inside the `Template`
object scope, so use of `$this` refers to the `Template` object. The following
is an example script:

```php
<html>
<head>
    <title><?= $this->title; ?></title>
</head>
<body>
    <p><?= "Hello " . $this->var . '!'; ?></p>
</body>
</html>
```

We can use any PHP code we would normally use. (This will require discipline
on the part of the template script author to restrict himself to
presentation-related logic only.)

We may wish to use the alternative PHP syntax for conditionals and loops:

```php
<?php if ($this->model->hasMessage()): ?>
    <p>The message is <?= $this->model->getMessage(); ?></p>
<?php endif; ?>

<ul>
<?php foreach ($this->list as $item): ?>
    <li><?= $item; ?></li>
<?php endforeach; ?>
</ul>
```


Escaping Output
---------------

***Aura View automatically escapes data assigned to the template when you
access that data.*** So, in general, you do not need to manually apply escaping
in your template scripts.

- Strings assigned to the template are automatically escaped as you access
  them; integers, floats, booleans, and nulls are not.

- If you assign an array to the template, its keys and values will be escaped
  as you access them.

- If you assign an object to the template, its properties and method returns
  will also be escaped as you access them.

Here is an example of the business logic to assign data to the template ...

```php
<?php
/**
 * @var object $obj An object with properties and methods.
 * @var array $arr An associative array.
 * @var string $str A string.
 * @var int|float $num An actual number (not a string representation).
 * @var bool $bool A boolean.
 * @var null $null A null value.
 */
$template->setData([
    'obj'  => $obj,
    'arr'  => $arr,
    'str'  => $str,
    'num'  => $num,
    'bool' => $bool,
    'null' => null,
]);
```

... and here is an example of the automatic escaping in the template:

```php
<?php
// strings are auto-escaped whenever you access them
echo $this->str;

// integers, floats, booleans, nulls, and resources are not escaped
if ($this->null === null || $this->bool === false) {
    echo $this->num;
}

// array keys and values are auto-escaped per the string/number/etc
// rules listed above
foreach ($this->arr as $key => $val) {
    // the key and value are already escaped for us
    echo $key . ': ' . $val;
}

// object properties and method returns are auto-escaped per the 
// string/number/etc rules listed above
echo $this->obj->property;
echo $this->obj->method();

// if the object implements Iterator or IteratorAggregate,
// the iterator keys and values are auto-escaped as well
foreach ($this->obj as $key => $val) {
    echo $key . ': ' . $val;
}
```

Note that automatic escaping occurs at *access* time, not at *assignment*
time, and only occurs when accessing *values assigned to the template*.

### Manual Escaping

If you create a variable of your own inside a template, you will need to
escape it yourself using the `escape()` helper:

```php
<?php
$var = "this & that";
echo $this->escape($var);
```

### Raw Data

If you want to access the assigned data without escaping applied, use the
`__raw()` method:

```php
<?php
// get the raw assigned string
echo $this->__raw()->str;

// get the count of an assigned array or object
echo count($this->__raw()->arr);

// see if the assigned array is empty
if (! $this->__raw()->arr) {
    echo "Array is empty.";
}

// get a raw property from an assigned object;
// either of the following will work:
echo $this->__raw()->obj->property;
echo $this->obj->__raw()->property;

// get a raw method result from an assigned object;
// either of the following will work:
echo $this->__raw()->obj->method();
echo $this->obj->__raw()->method();

// check if an object is an instanceof SomeClass
if ($this->__raw()->obj instanceof SomeClass) {
    // ...
}
```
    
Using the raw data is the only way to get a `count()` on an array or a
`Countable` object, or to find the class type of the underlying variable. This
is because the automatic escaping works by wrapping ("decorating") the
underlying variable with an escaper object. The decoration makes it possible
to auto-escape array keys and values, and object properties and methods, but
unfortunately hides things like `implements` and `instanceof` from PHP.

### Double Escaping

There is an escaping "gotcha" to look out for when manipulating values after
they are assigned to a template. If you use an assigned value and re-assign
it to the template, the new value will be double-escaped when you access it.

For example, given this business logic ...

```php
<?php
// business logic
$template->foo = "this & that";
```
    
... and this template script ...

```php
<?php
// template script
$this->bar = $this->foo . " & the other";
echo $this->bar;
```

... the output will be `"this &amp;amp; that &amp; the other"`. The output was
double-escaped; this is because the template escaped `$this->foo` for us when
we accessed it and assigned it to `$this->bar`, and then escaped `$this->bar`
for output as well.

When performing manipulations of this kind, use the `__raw()` values instead:

```php
<?php
// template script
$this->bar = $this->__raw()->foo . " & the other";
echo $this->bar;
```

Now the output will be `"this &amp; that &amp; the other"`, correctly escaped
only once.

Using Helpers
-------------

Aura View comes with various `Helper` classes to encapsulate common
presentation logic. These helpers are mapped to the `Template` object through
a `HelperLocator`. We can call a helper in one of two ways:

- As a method on the `Template` object

- Via `getHelper()` to get the helper as an object of its own

We have already discussed the `escape()` helper above. Other helpers that are
part of Aura View include:

- `$this->anchor($href, $text)` returns an `<a href="$href">$text</a>` tag

- `$this->attribs($list)` returns a space-separated attribute list from a
  `$list` key-value pair

- `$this->base($href)` returns a `<base href="$href" />` tag

- `$this->datetime($datestr, $format)` returns a formatted datetime string.

- `$this->image($src)` returns an `<img src="$src" />` tag.

- `$this->input($attribs, $value, $label, $label_attribs)` 
returns an `<input>` tag, optionally wrapped in a `<label>` tag
    
    In general `$this->input(['type' => $type], $value, $label, $label_attribs)` 
    
    `$value`, `$label` and `$label_attribs` are optional.
    
    Supported types:
    
    - `button` : clickable button
    - `checkbox` : checkbox
    - `color` : color picker
    - `date` : date control (year, month and day)
    - `datetime` : date and time control (year, month, day, hour, 
    minute, second, and fraction of a second, UTC time zone)
    - `datetime-local` : date and time control (year, month, day, 
    hour, minute, second, and fraction of a second, no time zone)
    - `email` : e-mail address
    - `file` : file-select field and a "Browse..." button for file uploads
    - `hidden` : hidden input field
    - `image` : image as the submit button
    - `month` : month and year control (no time zone)
    - `number` : field for entering a number
    - `password` : password field
    - `radio` : radio button
    - `range` : control for entering a number whose exact value is not 
    important (like a slider control)
    - `reset` : reset button (resets all form values to default values)
    - `search` : text field for entering a search string
    - `submit` : submit button
    - `tel` : telephone number
    - `text` : (default) single-line text field
    - `time` : time control (no time zone)
    - `url` : URL field
    - `week` : week and year control (no time zone)
    
    Examples are 
    
    - `$this->input(['type' => 'text', ... ], 'field value')`
    
    - `$this->input(['type' => 'checkbox', 'value' => 'yes'], 'yes')`


- `$this->metas()` provides an object with methods that add to, and then
  retrieve, a series of `<meta ... />` tags.

    - `$this->metas()->addHttp($http_equiv, $content)` adds an HTTP-equivalent
      meta tag to the helper.
    
    - `$this->metas()->addName($name, $content)` adds a meta-name tags to the
      helper.
    
    - `$this-metas()->get()` returns all the added tags from the helper.


- `$this->scripts()` provides an object with methods that add to, and then
  retrieve, a series of `<script ... ></script>` tags.

    - `$this->scripts()->add($src)` adds a script tag to the helper.
    
    - `$this->scripts()->addCond($exp, $src)` adds a script tag inside a
      conditional expression to the helper.
    
    - `$this->scripts()->get()` returns all the added tags from the helper.
    

- `$this->styles()` provides an object with methods that add to, and then
  retrieve, a series of `<link rel="stylesheet" ... />` tags.

    - `$this->styles()->add($href)` adds a style tag to the helper.
    
    - `$this->styles()->get()` returns all the added tags from the helper.


- `$this->textarea($attribs, $html)` Returns a `<textarea>`. `$html` is optional.

- `$this->title()` provides an object with methods that manipulate the
  `<title>...</title>` tag.

    - `$this->title()->set($title)` sets the title value.
    
    - `$this->title()->append($suffix)` adds on to the end of title value.
    
    - `$this->title()->prepend($prefix)` adds on to the beginning of the title
      value.
    
    - `$this->title()->get()` returns the title tag and value.


Advanced Usage
==============

The Template Finder
-------------------

Although we can use an absolute template script path with `fetch()`, it is
more powerful to specify one or more paths where template scripts are located.
Then we can `fetch()` based on a template name, and the `TemplateFinder` will
search through the assigned paths for that template. This allows us to specify
baseline templates, and override them as needed.

To tell the `TemplateFinder` where to find template scripts, get it from the
`Template` and use `setPaths()`.

```php
<?php
// business logic
$finder = $template->getTemplateFinder();

// set the paths where templates can be found
$finder->setPaths([
    '/path/to/templates/foo',
    '/path/to/templates/bar',
    '/path/to/templates/baz',
]);
```

Now when we call `fetch()`, the `Template` object will use the
`TemplateFinder` to look through those directories for the template script we
specified.

For example, if we `echo $template->fetch('tpl')` the `TemplateFinder` will
look through each of the directories in turn to use the first 'tpl.php'
template script it finds. This allows us to set up several locations for
templates, and put replacement templates in locations the `TemplateFinder`
will get to before the baseline ones.


Template Composition
--------------------

It often makes sense to split one template up into multiple pieces. This
allows us to keep logical separations between different pieces of content. We
might have a header section, a navigation section, a sidebar, and so on.

We can use the `$this->find()` method in a template script to find a template,
and then `include` it wherever we like. For example:

```php
<html>
<head>
    <?php include $this->find('head'); ?>
</head>
<body>
    <?php include $this->find('branding'); ?>
    <?php include $this->find('navigation'); ?>
    <p>Hello, <?= $this->var; ?>!</p>
    <?php include $this->find('foot'); ?>
</body>
</html>
```

Templates that we `include` in this way will share the scope of the template
they are included from.


Template Partials
-----------------

Template partials are a scope-separated way of splitting up templates. In
doing so, we can pass an array of variables to be used in the partial
template; they will be available under `$this` **in place of** the parent
template variables. For example, given the following partial template ...

```php
<?php
// partial template named '_item.php'.
echo "    <li>{$this->item}</li>" . PHP_EOL;
```

... we can use it from within another template as a partial:

```php
<?php
// main template. assume $this->list is an array of items.
foreach ($this->list as $item) {
    $template_name = '_item';
    $template_vars = ['item' => $item];
    echo $this->partial($template_name, $template_vars);
}
```

That will run the `$template_name` template script in a separate scope, and
the `$template_vars` array will be available as `$this` properties within that
separate scope.

> N.b.: We can also `fetch()` other templates from within a template;
> template scripts that are fetched in this way will *not* share the scope
> of the template they are called from (although `$this` will still be
> available).


Writing Helpers
---------------

There are two steps to adding new helpers:

1. Write a helper class

2. Add that class as a service in the `HelperLocator`

Writing a helper class is straightforward: extend `AbstractHelper` with an
`__invoke()` method. The following helper, for example, applies ROT-13 to a
string.

```php
<?php
namespace Vendor\Package\View\Helper;

use Aura\View\Helper\AbstractHelper;

class Obfuscate extends AbstractHelper
{
    public function __invoke($string)
    {
        return str_rot13($input);
    }
}
```

Now that we have a helper class, you can add it as a service in the
`HelperLocator` like so:

```php
<?php
// business logic
$locator = $template->getHelperLocator();
$locator->set('obfuscate', function () {
    return new \Vendor\Package\View\Helper\Obfuscate;
});
```
    
The service name in the `HelperLocator` doubles as a method name on the
`Template` object. This means we can call the helper via `$this->obfuscate()`:

```php
<?php
// template script
echo $this->obfuscate('plain text');
```

Note that we can use any method name for the helper, although it is generally
useful to name the service for the helper class.

Please examine the classes in `Aura\View\Helper` for more complex and powerful
examples.

Two Step View
=============

Aura.View supports the two step view pattern via the `TwoStep` class.

Instantiation
-------------

To instantiate the two-step view template, do the following:

```php
use Aura\View\Template;
use Aura\View\TemplateFinder;
use Aura\View\HelperLocator;
use Aura\View\TwoStep;
use Aura\View\FormatTypes;

$template = new Template(
    new TemplateFinder,
    new HelperLocator
);
$twostep = new TwoStep($template, new FormatTypes());
```

Setting and Getting Data
------------------------

Both the inner view and outer view share the same data. To set data we can use
the `setData()` method, and to get the data in the view we use `getData()`.

```php
$data = array(
    'hello' => 'Hello World!',
    'var'   => 'Another variable'
);
$twostep->setData($data);
```

Setting The Inner View
----------------------

The "inner view" specification is the first step in the two-step view; it
represents the core of the view (as opposed to the "outer" or "layout" view
wrapped around the core). The specification may be:

- (string) A template file name.

- (callable) A closure to execute; it should take no parameters.

- (array) An array where each element key is a `.format` name, and the
  corresponding element value is a string or a callable. This type is most
  useful when allowing for multiple views using the same data.

```php
// a string
$twostep->setInnerView('inner.php');

// a callable
$func = function() { return 'World!'; };
$twostep->setInnerView($func);

// an array of .format names
$twostep->setInnerView(
    [
        '.xml'  => 'hello.xml.php',
        '.html' => 'hello.html.php',
        '.json' => function () {
            function() use ($twostep) {
                return json_encode($twostep->getData());
            }
        },
    ]
);

```

Setting and Getting Inner View Path
-----------------------------------

We can set the template path of inner view via `setInnerPaths()`
or `addInnerPath()`.

The `setInnerPaths()` method resets all the previous paths where as 
`addInnerPath()` add the path to the already existing inner paths.

By using `getInnerPaths()` we get all the paths.

```php
$twostep->setInnerPaths([
    'first', 
    'second'
]);
$twostep->getInnerPaths(); // ['first', 'second']

$twostep->addInnerPath('third');
$twostep->getInnerPaths(); // ['first', 'second', 'third']

$twostep->setInnerPaths('fourth');
$twostep->getInnerPaths(); // ['fourth']
```

Setting The Outer View
----------------------

The "outer view" specification is the portion of the view that wraps around
the "inner view". It is typically called a "layout" or "site" template. As
with an inner view, the specification may be:

- (string) A template file name.

- (callable) A closure to execute; it should take no parameters.

- (array) An array where each element key is a .format name, and the
  corresponding element value is a string or a callable. This type is most
  useful when allowing for multiple views using the same data.

Setting and Getting Outer View Path
-----------------------------------

This is identical to the inner view path; substitute the word "Outer" for "Inner"
in the related methods.


Basic Usage
-----------

Let us assume you have `default.html.php` in `outerview` directory.

```php
<html>
<head>
<?php
    $this->title()->set($this->title);
    echo $this->title()->get();
?>
</head>
<body>
    <div>Two step view example</div>
    <?php echo $this->__raw()->inner_view; ?>
</body>
</html>
```

Let us assume you have `hello.html.php` with the contents as below 
in `innerview` directory.

```php
<div>Hello <?php echo $this->name; ?>, I am from Inner view :-)</div>
```

```php
<?php
require dirname(__DIR__) . '/aurasystem/package/Aura.View/src.php';

use Aura\View\Template;
use Aura\View\TemplateFinder;
use Aura\View\HelperLocator;
use Aura\View\TwoStep;
use Aura\View\FormatTypes;
use Aura\View\Helper\Title;

$template = new Template(
    new TemplateFinder,
    new HelperLocator([
        'title' => function () { return new Title; },
    ])
);
$twostep = new TwoStep($template, new FormatTypes());

$twostep->setInnerView([
    '.html' => 'hello.html.php',
    '.json' => function() use ($twostep) {
        return json_encode($twostep->getData());
    },
]);

$twostep->setOuterView([
    '.html' => 'default.html.php',
    '.json' => null
]);

$twostep->setInnerPaths([
    __DIR__ . '/innerview'
]);

$twostep->addOuterPath(
    __DIR__ . '/outerview'
);

$twostep->setData([
    'title' => 'Hello my awesome title',
    'name' => 'Bolivar',
]);

$twostep->setFormat('.html');

$twostep->setAccept([
    'text/html' => 1.0,
    'application/json' => 0.9,
]);

$contents = $twostep->render();

echo $contents;
```
Now when you execute the above code, you will see the two step view 
rendered.

```html
<html>
<head>
<title>Hello my awesome title</title>
</head>
<body>
    <div>Two step view example</div>
    <div>Hello and welcome to Aura.View Bolivar, I am from Inner view :-)</div>
</body>
</html>
```

Now change the `setFormat` method to `.json` and see the rendered output.

```php
{"title":"Hello my awesome title","name":"Bolivar"}
```

You can always change the variable used in `outerview` via the 
`setInnerViewVar` . By default it is `inner_view`.
