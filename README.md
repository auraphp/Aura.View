# Aura View

The Aura View package is an implementation of the
[`TemplateView`](http://martinfowler.com/eaaCatalog/templateView.html) and [`TwoStepView`] patterns,
with support for helpers and template finders. It adheres to
the "use PHP for presentation logic" ideology, and is preceded by systems such
as [`Savant`](http://phpsavant.com),
[`Zend_View`](http://framework.zend.com/manual/en/zend.view.html), and
[`Solar_View`](http://solarphp.com/class/Solar_View).  The presentation logic
can take the form of closures or PHP scripts proper.

## Foreword

### Requirements

This library requires PHP 5.4 or later, and has no userland dependencies.

### Installation

This library is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/view": "dev-develop-2"
    }
    
Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.View.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.View)

This library has 100% code coverage with [PHPUnit][]. To run the tests at the
command line, go to the _tests_ directory and issue `phpunit`.

[phpunit]: http://phpunit.de/manual/

### PSR Compliance

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Getting Started

### Instantiate manager

```php
<?php
use Aura\View\Finder;
use Aura\View\Helper;
use Aura\View\Manager;
use Aura\View\Template;

$view_manager = new Manager(
    new Template,   // template factory
    new Helper,     // bare-bones helper object
    new Finder,     // view-template finder
    new Finder      // layout-template finder
);
```

### Rendering A View Template ( Onestep View )

All templates for Aura View take the form of closures or of classes with an
__invoke() method.  We'll start by rendering the simplest possible template:
an inline closure.

```php
<?php
// data for the template
$data = [
    'name' => 'Bolivar',
    'email' => 'boshag@example.com',
];

// the view template
$view_template = function () {
    echo '<p>Hello ' . $this->safeHtml($this->name) . '. '
       . 'Your email address is ' . $this->safeHtml($this->email) . '.</p>';
};

// get the rendered output, then do what you like with it
$output = $view_manager->render($data, $view_template);
```

#### What's With $this ?

In the template closure, we see the use of `$this` to refer assigned data
and to helper methods.  The closure gets access to them via the use of the
`Closure::bindTo()` method, where the closure is bound to a `Template` object
with the assigned data and helper methods.


### Rendering a View and Layout ( Twostep View)

We can wrap the results of the view in a layout by adding a third parameter
to the `render()` call: a layout template.  Data for the view is shared with
the layout, so any changes to the data from the view will be accessible in
the layout (but not vice versa, since the view is processed before the layout).

```php
<?php
// data for the templates
$data = [
    'name' => 'Bolivar',
    'email' => 'boshag@example.com',
];

// the view template
$view_template = function () {
    // set a new variable into the data
    $this->title = 'Example Two-Step View';
    // show the same view output as before
    echo '<p>Hello ' . $this->safeHtml($this->name) . '. '
       . 'Your email address is ' . $this->safeHtml($this->email) . '.</p>';
};

// the layout template
$layout_template = function () {
    echo '<html>'
       . '<head><title>' . $this->safeHtml($this->title) . '</title></head>'
       . '<body>' . $this->content . '</body>'
       . '</html>';
};

// get the rendered output, then do what you like with it
$output = $view_manager->render($data, $view_template, $layout_template);
```

The output from the view template is automatically placed into a data property
called `$content` that the layout can use to wrap around.  If you want to use
a different property name, call `$view->setContentVar()` to set a new name.


* * *

- instantiate manager

- build some data

- one step: $manager->render($data, function () {...});

- two step: $manager->render($data, function () {...}, function () {...});

- extract the view & layout to the finder as named closures

- extract the finder view & layout to a named script

- using paths and prefix stacks in the finder

- using helpers

<?php
$view->render($data, function () {
    echo 'Hello, ' . $this->noun . '!';
});

?>

* * * 
