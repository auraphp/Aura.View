Introduction
============

The Aura View package is an implementation of the [TemplateView](http://martinfowler.com/eaaCatalog/templateView.html) pattern, with support for helpers and path stacks.  It adheres to the "use PHP for your presentation logic" ideology, and is preceded by systems such as [Savant](http://phpsavant.com), [Zend_View](http://framework.zend.com/manual/en/zend.view.html), and [Solar_View](http://solarphp.com/class/Solar_View).

This package depends on the [Aura DI](https://github.com/auraphp/aura.di) package.


Basic Usage
===========

Instantiation
-------------

The easiest way to instantiate a new template with all the associated helpers is to include the aura.di dependency sources, then call the `instance.php` script.

    <?php
    // business logic
    require_once '/path/to/aura.di/src.php';
    $template = require '/path/to/aura.view/script/instance.php';

Then use the Template object to `fetch()` the output of a template script.

    <?php
    // business logic
    echo $template->fetch('/path/to/tpl.php');

Alternatively, you can add the `aura.di/src` and `aura.view/src` directories to your autoloader, and instantiate manually:

    <?php
    use aura\di\Container;
    use aura\di\Forge;
    use aura\di\Config;
    use aura\view\Template;
    use aura\view\Finder;
    $template = new Template(
        // a helper container
        new Container(new Forge(new Config)),
        // a template finder
        new Finder
    );
    
Assigning Data
--------------

You can assign variables to the template script by setting properties on the template object, like so:

    <?php
    // business logic
    $template->var = 'World';

You can then reference those properties from within the template script using `$this`:

    <?php
    // template script
    $e = $this->getHelper('escape');
    echo $e($this->var);

You can assign multiple data properties at once using `setData()`:

    <?php
    // business logic
    $template->setData(array(
        'foo' => 'Value of foo',
        'bar' => 'Value of bar',
    ));

Note that the array keys will map to object properties, so make sure they are valid as property names.


Writing Template Scripts
------------------------

Aura View template scripts are written in plain PHP and do not require you to learn a new markup language.  The `Template` object executes the template scripts inside the `Template` object scope, so use of `$this` refers to the `Template` object.  The following is an example script:

    <?php $e = $this->getHelper('escape'); ?>
    <html>
    <head>
        <title><?php echo $e($this->title) ?></title>
    </head>
    <body>
        <p><?php
            echo "Hello " . $e($this->var) . '!';
        ?></p>
    </body>
    </html>

You can use any PHP code you would normally use. (This may require discipline on the part of the template script author to restrict himself to presentation-related logic only.)  You may wish to use the alternative PHP syntax for conditionals and loops:

    <?php if ($this->message): ?>
        <p>The message is <?php echo $e($this->message); ?></p>
    <?php endif; ?>
    
    <ul>
    <?php foreach ($this->list as $item): ?>
        <li><?php echo $e($item); ?></li>
    <?php endforeach; ?>
    </ul>


Using Helpers
-------------

Aura View comes with various `Helper` classes to encapsulate common presentation logic.  These helpers are mapped to the `Template` object through a `HelperContainer`. They can be called as methods on the `Template`.  The default helpers are added to the `HelperContainer` by the `instance.php` script.

The single-most important helper is `$this->escape()`. You should use it every time you need to echo or print assigned variables.  (All of the other helpers apply escaping automatically.)

The other helpers include:

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
    
    - `$this->scripts()->get()` returns all the added tags from the helper.

- `$this->styles()` provides an object with methods that add to, and then retrieve, a series of `<link rel="stylesheet" ... />` tags.

    - `$this->styles()->add($href)` adds a style tag to the helper.
    
    - `$this->styles()->get()` returns all the added tags from the helper.

- `$this->title()` provides an object with methods that manipulate the `<title>...</title>` tag.

    - `$this->title()->set($title)` sets the title value.
    
    - `$this->title()->append($suffix)` adds on to the end of title value.
    
    - `$this->title()->prepend($prefix)` adds on to the beginning of the title value.
    
    - `$this->title()->get()` returns the title tag with the title itself escaped properly.
    
    - `$this->title()->getRaw()` returns the title tag, with the title itself *not* escaped.

Advanced Usage
==============

The Template Finder
-------------------

Partial Templates
-----------------

Template Composition
--------------------

Writing Helpers
---------------

