Aura View
=========

The Aura View package is an implementation of the [TemplateView](http://martinfowler.com/eaaCatalog/templateView.html) pattern, with support for automatic escaping, path stacks, and helpers.  It adheres to the "use PHP for presentation logic" ideology, and is preceded by systems such as [Savant](http://phpsavant.com), [Zend_View](http://framework.zend.com/manual/en/zend.view.html), and [Solar_View](http://solarphp.com/class/Solar_View).


Basic Usage
===========

Instantiation
-------------

The easiest way to instantiate a new `Template` with all the associated helpers is to include the `instance.php` script.

    <?php
    $template = require '/path/to/Aura.View/scripts/instance.php';

Then use the `Template` object to `fetch()` the output of a template script.

    <?php
    echo $template->fetch('/path/to/tpl.php');

Alternatively, we can add the `Aura.View` package to an autoloader, and instantiate manually:

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

(Note that if we instantiate manually, we will need to configure the `HelperLocator` manually to add helper services. See the "Helpers" section near the end of this page for more information.)


Assigning Data
--------------

We can assign variables to the template script by setting properties on the `Template` object, like so:

    <?php
    // business logic
    $template->var = 'this & that';

We can then reference those properties from within the template script using `$this`:

    <?php
    // template script
    echo $this->var;

We can add multiple data properties at once, merging new values with existing ones, by using `addData()`.

    <?php
    // business logic
    $template->addData([
        'foo' => 'Value of foo',
        'bar' => 'Value of bar',
    ]);

(Note that the array keys will map to object properties, so make sure they are valid as property names.)

Finally, we can replace all the `Template` data values at once using `setData()`.

    <?php
    // business logic
    // this will remove $var, $foo, and $bar from the template
    $template->setData([
        'baz' => 'Value of baz',
        'dib' => 'Value of dib',
    ]);


Writing Template Scripts
------------------------

Aura View template scripts are written in plain PHP and do not require a new markup language.  The template scripts are executed inside the `Template` object scope, so use of `$this` refers to the `Template` object.  The following is an example script:

    <html>
    <head>
        <title><?php echo $this->title ?></title>
    </head>
    <body>
        <p><?php
            echo "Hello " . $this->var . '!';
        ?></p>
    </body>
    </html>

We can use any PHP code we would normally use. (This may require discipline on the part of the template script author to restrict himself to presentation-related logic only.)  We may wish to use the alternative PHP syntax for conditionals and loops:

    <?php if ($this->message): ?>
        <p>The message is <?php echo $this->message; ?></p>
    <?php endif; ?>
    
    <ul>
    <?php foreach ($this->list as $item): ?>
        <li><?php echo $item; ?></li>
    <?php endforeach; ?>
    </ul>


Escaping Output
---------------

In general, you do not need to manually apply escaping in your template scripts.  Aura View automatically escapes data assigned to the template when you *access* that data.

- Strings assigned to the template are automatically escaped as you access them; integers, floats, booleans, and nulls are not.

- If you assign an array to the template, its keys and values will be escaped as you access them.

- If you assign an object to the template, its properties and method returns will also be escaped as you access them.

Here is an example of the business logic to assign data to the template:

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
        'str'  => $str,
        'num'  => $num,
        'bool' => $bool,
        'null' => $null,
        'arr'  => $arr,
        'obj'  => $obj,
    ]);

And here is an example of the automatic escaping in the template:

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

Note that automatic escaping occurs at *access* time, not as *assignment* time, and only occurs when accessing *values assigned to the template*.

If you create a variable of your own inside a template, you will need to escape it yourself using the `escape()` helper:

    <?php
    $var "this & that";
    echo $this->escape($var);

If you want to access the assigned data without escaping applied, use the `__raw()` method:

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
    
Using the raw data is the only way to get a `count()` on an array or a `Countable` object, or to find the class type of the underlying variable. This is because the automatic escaping works by wrapping ("decorating") the underlying variable with an escaper object. The decoration makes it possible to auto-escape array keys and values, and object properties and methods, but unfortunately hides things like `implements` and `instanceof` from PHP.


Using Helpers
-------------

Aura View comes with various `Helper` classes to encapsulate common presentation logic.  These helpers are mapped to the `Template` object through a `HelperLocator`. We can call a helper in one of two ways:

- As a method on the `Template` object

- Via `getHelper()` to get the helper as an object of its own

We have already discussed the `escape()` helper above. Other helpers that are part of Aura View include:

- `$this->anchor($href, $text)` returns an `<a href="$href">$text</a>` tag

- `$this->attribs($list)` returns a space-separated attribute list from a `$list` key-value pair

- `$this->base($href)` returns a `<base href="$href" />` tag

- `$this->datetime($datestr, $format)` returns a formatted datetime string.

- `$this->image($src)` returns an `<img src="$src" />` tag.

- `$this->metas()` provides an object with methods that add to, and then retrieve, a series of `<meta ... />` tags.

    - `$this->metas()->addHttp($http_equiv, $content)` adds an HTTP-equivalent meta tag to the helper.
    
    - `$this->metas()->addName($name, $content)` adds a meta-name tags to the helper.
    
    - `$this-metas()->get()` returns all the added tags from the helper.

- `$this->scripts()` provides an object with methods that add to, and then retrieve, a series of `<script ... ></script>` tags.

    - `$this->scripts()->add($src)` adds a script tag to the helper.
    
    - `$this->scripts()->addCond($exp, $src)` adds a script tag inside a conditional expression to the helper.
    
    - `$this->scripts()->get()` returns all the added tags from the helper.

- `$this->styles()` provides an object with methods that add to, and then retrieve, a series of `<link rel="stylesheet" ... />` tags.

    - `$this->styles()->add($href)` adds a style tag to the helper.
    
    - `$this->styles()->get()` returns all the added tags from the helper.

- `$this->title()` provides an object with methods that manipulate the `<title>...</title>` tag.

    - `$this->title()->set($title)` sets the title value.
    
    - `$this->title()->append($suffix)` adds on to the end of title value.
    
    - `$this->title()->prepend($prefix)` adds on to the beginning of the title value.
    
    - `$this->title()->get()` returns the title tag and value.


Advanced Usage
==============

The Template Finder
-------------------

Although we can use an absolute template script path with `fetch()`, it is more powerful to specify one or more paths where template scripts are located. Then we can `fetch()` based on a template name, and the `TemplateFinder` will search through the assigned paths for that template.  This allows us to specify baseline templates, and override them as needed.

To tell the `TemplateFinder` where to find template scripts, get it from the `Template` and use `setPaths()`.

    <?php
    // business logic
    $finder = $template->getTemplateFinder();
    
    // set the paths where templates can be found
    $finder->setPaths([
        '/path/to/templates/foo',
        '/path/to/templates/bar',
        '/path/to/templates/baz',
    ]);

Now when we call `fetch()`, the `Template` object will use the `TemplateFinder` to look through those directories for the template script we specified.

For example, if we `echo $template->fetch('tpl')` the `TemplateFinder` will look through each of the directories in turn to use the first 'tpl.php' template script it finds.  This allows us to set up several locations for templates, and put replacement templates in locations the `TemplateFinder` will get to before the baseline ones.


Template Composition
--------------------

It often makes sense to split one template up into multiple pieces.  This allows us to keep logical separations between different pieces of content.  We might have a header section, a navigation section, a sidebar, and so on.

We can use the `$this->find()` method in a template script to find a template, and then `include` it wherever we like.  For example:

    <?php
        // template script
        $e = $this->getHelper('escape');
    ?>
    <html>
    <head>
        <?php include $this->find('head'); ?>
    </head>
    <body>
        <?php include $this->find('branding'); ?>
        <?php include $this->find('navigation'); ?>
        <p>Hello, <?php echo $e($this->var); ?>!</p>
        <?php include $this->find('footer'); ?>
    </body>
    </html>

Templates that we `include` in this way will share the scope of the template they are included from.


Template Partials
-----------------

Template partials are a scope-separated way of splitting up templates.  We can `fetch()` other templates from within a template; template scripts that are fetched in this way will *not* share the scope of the template they are called from (although `$this` will still be available).  In addition, we can pass an array of variables to be [`extract`](http://php.net/extract)ed into the partial template.

For example, given the following partial template ...

    <?php
    // partial template named '_partial.php'.
    // note that we use $name, not $this->name.
    $e = $this->getHelper('escape');
    echo "    <li>" . $e($name) . "</li>" . PHP_EOL;

... we can `fetch()` it from within another template:

    <?php
    // main template. assume $this->list is an array of names.
    foreach ($this->list as $item) {
        $template_name = '_partial';
        $template_vars = ['name' => $item];
        echo $this->fetch($template_name, $template_vars);
    }

That will run the `$template_name` template script in a separate scope, and extract the `$template_vars` array within that separate scope.


Writing Helpers
---------------

There are two steps to adding new helpers:

1. Write a helper class

2. Add that class as a service in the `HelperLocator`

Writing a helper class is straightforoward:  extend `AbstractHelper` with an `__invoke()` method.  The following helper, for example, applies ROT-13 to a string.

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

Now that we have a helper class, you can add it as a service in the `HelperLocator` like so:

    <?php
    // business logic
    $hl = $template->getHelperLocator();
    $hl->set('obfuscate', function() {
        return new \Vendor\Package\View\Helper\Obfuscate;
    });
    
The service name in the `HelperLocator` doubles as a method name on the `Template` object.  This means we can call the helper via `$this->obfuscate()`:

    <?php
    // template script
    echo $this->obfuscate('plain text');

Note that we can use any method name for the helper, although it is generally useful to name the service for the helper class.

Please examine the classes in `Aura\View\Helper` for more complex and powerful examples.
