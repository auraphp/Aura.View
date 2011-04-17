Introduction
============

The Aura View package is an implementation of the [TemplateView](http://martinfowler.com/eaaCatalog/templateView.html) pattern, with support for helpers and path stacks.  It adheres to the "use PHP for presentation logic" ideology, and is preceded by systems such as [Savant](http://phpsavant.com), [Zend_View](http://framework.zend.com/manual/en/zend.view.html), and [Solar_View](http://solarphp.com/class/Solar_View).

This package depends on the [Aura DI](https://github.com/auraphp/aura.di) package.


Basic Usage
===========

Instantiation
-------------

The easiest way to instantiate a new template with all the associated helpers is to include the aura.di dependency sources, then call the `instance.php` script.

    <?php
    // business logic
    require_once '/path/to/aura.di/src.php';
    $template = require '/path/to/aura.view/scripts/instance.php';

Then use the Template object to `fetch()` the output of a template script.

    <?php
    // business logic
    echo $template->fetch('/path/to/tpl.php');

Alternatively, we can add the `aura.di/src` and `aura.view/src` directories to an autoloader, and instantiate manually:

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

Note that if we instantiate manually, we will need to configure the `Container` manually to add helper services.


Assigning Data
--------------

We can assign variables to the template script by setting properties on the template object, like so:

    <?php
    // business logic
    $template->var = 'World';

We can then reference those properties from within the template script using `$this`:

    <?php
    // template script
    $e = $this->getHelper('escape');
    echo $e($this->var);

We can assign multiple data properties at once using `setData()`:

    <?php
    // business logic
    $template->setData(array(
        'foo' => 'Value of foo',
        'bar' => 'Value of bar',
    ));

Note that the array keys will map to object properties, so make sure they are valid as property names.


Writing Template Scripts
------------------------

Aura View template scripts are written in plain PHP and do not require a new markup language.  The `Template` object executes the template scripts inside the `Template` object scope, so use of `$this` refers to the `Template` object.  The following is an example script:

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

We can use any PHP code we would normally use. (This may require discipline on the part of the template script author to restrict himself to presentation-related logic only.)  We may wish to use the alternative PHP syntax for conditionals and loops:

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

Aura View comes with various `Helper` classes to encapsulate common presentation logic.  These helpers are mapped to the `Template` object through a `HelperContainer`. We can call a helper in one of two ways:

- As a method on the `Template` object

- Via `getHelper()` to get the helper as an object of its own

The single-most important helper is `$this->escape()`. We should use it every time we need to echo or print assigned variables.  (All of the other helpers apply escaping automatically.)  You can call it like so:

    <?php
    // template script
    echo $this->escape($this->var);

Or like so:

    <?php
    // template script
    $e = $this->getHelper('escape');
    echo $e($this->var);

Other helpers that are part of Aura View include:

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

Although we can use an absolute template script path with `fetch()`, it is more powerful to specify one or more paths where template scripts are located. Then we can `fetch()` based on a template name, and the `Finder` will search through the assigned paths for that template.  This allows us to specify baseline templates, and override them as needed.

To tell the `Finder` where to find template scripts, get it from the `Template` and use `setPaths()`.

    <?php
    // business logic
    $finder = $template->getFinder();
    
    // set the paths where templates can be found
    $finder->setPaths(array(
        '/path/to/templates/foo',
        '/path/to/templates/bar',
        '/path/to/templates/baz',
    ));

Now when we call `fetch()`, the `Template` object will use the `Finder` to look through those directories for the template script we specified.

For example, if we call `echo $template->fetch('tpl');` the `Finder` will look through each of the directories in turn to use the first 'tpl.php' template script it finds.  This allows us to set up several locations for templates, and put replacement templates in locations the `Finder` will get to before the baseline ones.


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
        <p>Hello, <?php echo $this->var; ?>!</p>
        <?php include $this->find('footer'); ?>
    </body>
    </html>

Templates that we `include` in this way will share the scope of the template they are included from.


Template Partials
-----------------

Template partials are a scope-separated way of splitting up templates.  We can `fetch()` other templates from within a template; template scripts that are fetched in this way will *not* share the scope of the template they are called from.  In addition, we can pass an array of variables to be [extract](http://php.net/extract)ed into the partial template.

For example, given the following partial template ...

    <?php
    // partial template named '_partial.php'.
    // note that we use $name, not $this->name.
    $e = $this->getHelper('escape');
    echo "    <li>" . $e($name) . "</li>" . PHP_EOL;

... we can `fetch()` it from within another template:

    <?php
    // main template. assume $this->list is an array
    // of names.
    foreach ($this->list as $item) {
        $template_name = '_partial';
        $template_vars = array('name' => $item);
        echo $this->fetch($template_name, $template_vars);
    }

That will run the `$template_name` template script in a separate scope, and extract the `$template_vars` array in that separate scope.


Writing Helpers
---------------

