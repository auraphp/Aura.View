Aura.View Helpers
=========

This is some basic documentation for the view helpers shipped with Aura.View.

Anchor
-----

Creates an anchor tag

**Syntax:**

```php
<?php echo $this->anchor(string $href , string $text [, array $attribs]); ?>
```

**Parameters**

    @param $href: The href  
    @param $text: The link text    
    @param $attribs: Optional attributes for the anchor tag

**Example:**

```php
<?php echo $this->anchor('/home/about', 'About Us', ['class'=>'button', 'id'=>'ab-link']); ?>
```

**Output:**

    <a href="/home/about" class="button" id="ab-link">About Us</a>

--------------------

Attribs
-----

Creates a string of attributes from an array

**Syntax:**

```php
<?php echo $this->attribs(array $attribs [, array $skip]); ?>
```

**Parameters**

    @param $attribs: Array of attributes  
    @param $skip: Array of attributes to skip    

**Example 1:**

```php
<?php 
$attribs = [
    'class'=>'button secondary',
    'id'=>'my-btn'
];
?>
<div <?php echo $this->attribs($attribs); ?>>Button</div>
```

**Output:**

    <div class="button secondary" id="my-btn">Button</div>


**Example 2:**

```php
<?php 
$attribs = [
    'class'=>'button secondary',
    'id'=>'my-btn'
];
$skip = [
    'id'
];
?>
<div <?php echo $this->attribs($attribs, $skip); ?>>Button</div>
```

**Output:**

    <div class="button secondary">Button</div>

--------------------

Base
-----

Returns a <code>&lt;base .../&gt;</code> tag

**Syntax:**

```php
<?php echo $this->base(string $href); ?>
```

**Parameters**

    @param $href: The base href     

**Example:**

```php
<?php echo $this->base('/folder'); ?>
```

**Output:**

    <base href="/folder" />

---------------

Datetime
--------

Returns a formatted timestamp based on date() format

**Syntax:**

```php
<?php echo $this->datetime(string $spec [, string $format = 'default']); ?>
```

**Parameters**

    @param $spec: A date-time string that can be formatted via strtotime()
    @param $foramt: One of 4 predefined formats, or a custom date() format

Predefined formats are as follows:

 - date ('Y-m-d')
 - time ('H:i:s')
 - datetime ('Y-m-d H:i:s')
 - default ('Y-m-d H:i:s')

**Example 1:**

```php
<?php echo $this->datetime('Now'); ?>
```

**Output:**

    2013-10-04 12:40:37

**Example 2:**

```php
<?php echo $this->datetime('January 1, 2014 10:30pm'); ?>
```

**Output:**

    2014-01-01 22:30:00

**Example 3:**

```php
<?php echo $this->datetime('2014-05-12', 'F jS, Y'); ?>
```

**Output:**

    May 12th, 2014

**Example 4:**

```php
<?php echo $this->datetime('Now', 'time'); ?>
```

**Output:**

    12:45:38

---------------

Escape
------

Escapes strings; wraps objects and arrays in escaper objects; leaves booleans/numbers/resources/nulls alone.

Strings assigned to the template are automatically escaped as you access them; integers, floats, booleans, and nulls are not.

 - If you assign an array to the template, its keys and values will be escaped as you access them.
 - If you assign an object to the template, its properties and method returns will also be escaped as you access them.

**Syntax:**

```php
<?php 
// Manually escaping $val created in the template/view
$val = 'some string';
echo $this->escape(mixed $val); 
?>
```

**Parameters**

    @param $val: The value to escape
    
**Example 1:**

```php
<?php 
$val = '<script>';
echo $this->escape($val); 
?>
```

**Output:**

    &lt;script&gt;
    
**Example 2:** 

```php
<?php
// In a controller...
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
...

<?php
// Automatic escaping in the template object

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
    
-----------

Image
-----

Returns an <code>&lt;img ... /&gt;</code> tag

**Syntax**

```php
<?php echo $this->image(string $src [, $attribs = [] ]); ?>
```

**Parameters**

    @src: The href to the image
    @attribs: Optional attributs for the image tag

**Example:**

```php
<?php echo $this->image('/images/clown.png', ['id'=>'img-clown', 'class'=>'thumbnail']); ?>
```

**Output:**

    <img id="img-clown" class="thumbnail" src="/images/clown.png" alt="clown.png" />
    
-----------

Link
-----

Returns a stack of <code>&lt;link ... /&gt;</code> tags as a single block
    
**Syntax:**

```php
<?php echo $this->links()
                ->add([
                    'rel'=>'icon', 
                    'type'=>'image/x-icon', 
                    'href'=>'/favicon.ico'
                ])
                ->add([
                    'href'=>'/apple-touch-icon-114.png',
                    'rel'=>'apple-touch-icon',
                    'sizes'=>'57x57'
                ])
                ->get();
?>
```

**Output:**

    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link href="/apple-touch-icon-114.png" rel="apple-touch-icon" sizes="57x57" />
    
-----------

Metas
-----

Helper for a stack of <code>&lt;meta ... /&gt;</code> tags.

**Syntax:**

```php
<?php 
$metas = $this->metas();
$metas->add(array $attribs [, $pos = 100] );
$metas->addHttp(string $http_equiv, string $content [, $pos = 100] );
$metas->addName(string $name, string $content [, $pos = 100] );
```

**Parameters**

    // add()
    @param $attribs: Array of attributes for a generic meta tag
    @param $pos: The index key for sorting the metas when returned
    
    // addHttp()
    @param $http_equiv: The http-equiv attribute for the tag
    @parm $content: The content attribute for the tag
    @param $pos: The index key for sorting the metas when returned
    
    // addName()
    @param $name: The name attribute for the tag
    @parm $content: The content attribute for the tag
    @param $pos: The index key for sorting the metas when returned

**Examples:**

```php
<?php
$metas = $this->metas();
$metas->add([
    'charset'=>'utf-8'
]);
$metas->addHttp('X-UA-Compatible', 'IE=edge');
$metas->addName('csrf-param', 'authenticity_token', 80);
echo $metas->get();
?>
```

**Output:**

    <meta name="csrf-param" content="authenticity_token" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

-----------

UL
-----

Creates an unordered list.

**Syntax:**

```php
<?php echo $this->ul( [array $attribs = [] ] )
                ->item( string $html [, array $attribs = [] ] )
                ->fetch();
?>
// OR
<?php echo $this->ul( [array $attribs = [] ] )
                ->items( array $items [, array $attribs = [] ] )
                ->fetch();
?>
```

**Parameters**

    @param $attribs: Array of attribs for the UL tag
    @param $html: HTML for the li tag
    @param $attribs: Array of attribs for the list item tag
    
    @param $items: Array of html for list items.
    @param $attribs: Array of attribs for each list item tag


**Example 1**

```php
<?php echo $this->ul()
                ->item('List item one')
                ->item('<a href="#">List item two</a>')
                ->fetch();
?>
```

**Output**

    <ul>
        <li>List item one</li>
        <li><a href="#">List item two</a></li>
    </ul>


**Example 2**

```php
<?php echo $this->ul(['class'=>'mylist', 'id'=>'ul-test'])
                ->item('List item one', ['class'=>'list-item'])
                ->item('<a href="#">List item two</a>')
                ->fetch();
?>
```

**Output**

    <ul class="mylist" id="ul-test">
        <li class="list-item">List item one</li>
        <li><a href="#">List item two</a></li>
    </ul>

**Example 3**

```php
<?php 
$items = [
    'My list item one',
    'My list item two',
    'My list item three'
];
echo $this->ul(['class'=>'mylist', 'id'=>'ul-test'])
          ->items($items, ['class'=>'list-items'])
          ->fetch();
?>
```

**Output**

    <ul class="mylist" id="ul-test">
        <li class="list-items">My list item one</li>
        <li class="list-items">My list item two</li>
        <li class="list-items">My list item three</li>
    </ul>

-----------

OL
-----

Same as UL but using an ordered list.

---------------

Scripts
-----

Helper to add a stack of script tags to the page. Optionally use conditionals.

**Syntax**

```php
<?php 
$scripts = $this->scripts();
$scripts->add(string $src [, array $attribs = [] ] [, int $pos = 100 ] );
$scripts->addCond(string $exp, string $src [, array $attribs = [] ] [, int $pos = 100 ] );
echo $scripts->get();
?>
```

**Parameters**

    @param $src: The script source (href)
    @param $attribs: array of attribs for the script tag
    @param $pos: The position in the stack. Used for sorting.    
    @param $exp: Conditional expression for the script

**Example 1**

```php
<?php 
$scripts = $this->scripts();
$scripts->add('/my/path/to/javascript.js');
$scripts->addCond('lte IE 7', '/my/path/to/ie_lt7.js');
echo $scripts->get();
?>
```

**Output**

    <script src="/my/path/to/javascript.js" type="text/javascript"></script>
    <!--[if lte IE 7]><script src="/my/path/to/ie_lt7.js" type="text/javascript"></script>
    
**Example 2**

```php
<?php 
$scripts = $this->scripts();
$scripts->add('/my/path/to/javascript.js', ['charset'=>'UTF-8']);

// 20 comes before 100. This should output before the previous script tag
$scripts->add('/my/path/to/javascript.js', ['charset'=>'UTF-8'], 20);
$scripts->addCond('lte IE 7', '/my/path/to/ie_lt7.js');
echo $scripts->get();
?>
```

**Output**

    <script src="/my/path/to/jquery.js" charset="UTF-8" type="text/javascript"></script>
    <script src="/my/path/to/javascript.js" charset="UTF-8" type="text/javascript"></script>
    <!--[if lte IE 7]><script src="/my/path/to/ie_lt7.js" type="text/javascript"></script><![endif]-->

-----------

Styles
-----

Helper to add a stack of style tags to the page. Optionally use conditionals.

**Syntax**

```php
<?php 
$styles = $this->styles();
$styles->add(string $href [, array $attribs = [] ] [, int $pos = 100 ] );
$styles->addCond(string $exp, string $href [, array $attribs = [] ] [, int $pos = 100 ] );
echo $styles->get();
?>
```

**Parameters**

    @param $href: The href to the css file
    @param $attribs: array of attribs for the style tag 
    @param $pos: The position in the stack. Used for sorting.    
    @param $exp: Conditional expression for the stylesheet

**Example 1**

```php
<?php 
$styles = $this->styles();
$styles->add('/my/path/to/mystyles.css');
$styles->addCond('lte IE 8', '/my/path/to/ie_lt8.css');
echo $styles->get();
?>
```

**Output**

    <!--[if lte IE 8]><link rel="stylesheet" href="/my/path/to/ie_lt8.css" type="text/css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="/my/path/to/mystyles.css" type="text/css" media="screen" />
    
---------------

Tag
-----

Helper to output any tag. 

**Syntax**

```php
<?php echo $this->tag( string $tag [, array $attribs = [] ] ); ?>
```
**Parameters**

    @param $tag: The html tag
    @param $attribs: Array of ptional attributes for the tag
    
**Example**

```php
<?php 
echo $this->tag('div', [
    'class'=>'foo',
    'id'=>'bar'
]);
?>
Lorem ipsum dolar...
<?php echo $this->tag('/div'); ?>
```

**Output**

    <div class="foo" id="bar">Lorem ipsum dolar...
    </div>
    
----------

Title
-----

Outputs the HTML title tag

**Syntax**

```php
<?php 
$title = $this->title(); 
$title->set( string $title );
$title->append( string $text );
$title->prepend( string $text );
?>
```

**Parameters**

    @param $title: The page title
    // append
    @param $text: Text you would like appended to the title
    // prepend
    @param $text: Text you would like prepended to the title
    
**Example 1**

```php
<?php
$title = $this->title();
$title->set('Welcome to my web application');
echo $title->get();
?>
```

**Output**

    <title>Welcome to my web application</title>
    
**Example 2**

```php
<?php
$title = $this->title();
$title->set('My Web Application');
$title->prepend('My Page : ');
echo $title->get();
?>
```
    
**Output**

    <title>My Page : My Web Application</title>

**Example 3**

```php
<?php
$title = $this->title();
$title->set('My Web Application');
$title->append(' : My Page');
echo $title->get();
?>
```

**Output**

    <title>My Web Application : My Page</title>
    
---------------


Checkboxes
-----

Helper for series of <code>&lt;input type="checkbox" /&gt;</code> tags.

**Syntax**

```php
<?php 
echo $this->checkboxes( array $attribs, array $options [, array $checked = [] ] [, string $separator = null ]
); 
?>
```

**Parameters**

    @param $attribs: Base attributes for the checkboxes
    @param $options: The checkbox values and text
    @param $checked: An array of checked values (optional)
    @param $separator: A separator between checkboxes (optional)

**Example**

```php
<?php
$attribs = ['name'=>'subscribe'];

// If the options aren't keyed, it will be a 0-based array
$options = [
    'apples'=>'Apples',
    'oranges'=>'Oranges'
];

$checked = ['apples'];
echo $this->checkboxes($attribs, $options, $checked);
?>
```

**Output**

    <label><input name="subscribe" type="checkbox" value="apples" checked="checked" /> Apples</label>
    <label><input name="subscribe" type="checkbox" value="oranges" /> Oranges</label>

---------------

Input
-----

Returns an html input tag

**Syntax**

```php
<?php echo $this->input( array $attribs [, string $value = null ] ); ?>
```

**Parameters**

    @param $attribs: array of attributes for the input tag
    @param $value: The current value of the input

**Example**

```php
<?php echo $this->input([
    'type'=>'text',
    'name'=>'first_name',
    'class'=>'input-text'], 
    'Bolivar'
); ?>

<?php echo $this->input([
    'type'=>'button',
    'name'=>'search',
    'class'=>'input-button'
]); ?>

```

**Output**

    <input type="text" name="first_name" class="input-text" value="Bolivar" />

    <input type="button" name="search" class="input-button" />
    
---------------

Radios
-----

Helper for outputting a series our radio input fields

**Syntax**

```php
<?php echo $this->radios( array $attribs, array $options[ , mixed $checked = null] [, string $separator ] ); ?>
```

**Parameters**

    @param $attribs: Array of attributes for the input tag
    @param $options: Array of values and labels for the radio inputs
    @param $checked: The checked value. If this value matches a radio value, it will be checked.
    @param $separator: A separator between radios.
    
**Example**

```php
<?php
$attribs = [
    'name'=>'subscribe'
];
$options = [
    'yes'=>'Yes, please',
    'no'=>'No, thank you'
];
$checked = 'yes';
echo $this->radios($attribs, $options, $checked);
?>
```

**Output**

    <label><input name="subscribe" type="radio" value="yes" /> Yes</label>
    <label><input name="subscribe" type="radio" value="no" checked="checked" /> No</label>
    
----------

Select
-----

Helper to return a select tag with options. Supports optgroup.

**Syntax**

```php
<?php echo $this->select( array $attribs [, array $options = [] ] [, $value = null ] ); ?>
```

**Parameters**

    @param $attribs: Attributes for the select tag
    @param $options: Array of options for the select tag. 
    
**Syntax**

```php
<?php
$select = $this->select( [ array $attribs = [] ] );
$select->options( array $options [, array $attribs = [] ] );
echo $select->fetch();
?>
```

**Parameters**

    @param $options: An array of options where the key is the option
                     value, and the value is the option label.  If the value is an array,
                     the key is treated as a label for an optgroup, and the value is 
                     a sub-array of options
    @param $attribs: Attributes for each option tag

**Syntax**

```php
<?php
$select = $this->select( [ array $attribs = [] ] );
$select->optgroup( string $label [, array $attribs = [] ] );
echo $select->fetch();
?>
```

**Parameters**

    @param $label: Optgroup label
    @param $attribs: Attributes for the optgroup tag

**Syntax**

```php
<?php
$select = $this->select( [ array $attribs = [] ] );
$select->option( string $value, string $lable [, array $attribs = [] ] );
echo $select->fetch();
?>
```

**Parameters**

    @param $value: Option value
    @param $label: Option label
    @param $attribs: Attributes for the option tag

**Syntax**

```php
<?php
$select = $this->select( [ array $attribs = [] ] );
$select->selected( mixed $selected );
echo $select->fetch();
?>
```

**Parameters**

    @param $selected: The selected value or values. Either an array or 
                      instance of \Traversable

**Example 1**

```php
<?php
$options = [
    'one'=>'One potato',
    'two'=>'Two potato',
    'three'=>'Three potato',
];
$attribs = [
    'class'=>'input-select',
    'placeholder'=>'Select Something' // the first, no value item (optional)
];
echo $this->select($attribs, $options);
?>
```

**Output**
    
    <select class="input-select">
        <option value="">Select Something</option>
        <option value="one">One potato</option>
        <option value="two">Two potato</option>
        <option value="three">Three potato</option>
    </select>


**Example 2**

```php
<?php
$options = [
    'AB'=>[
        'yeg'=>'Edmonton',
        'yyc'=>'Calgary'
    ],
    'SK'=>[
        'yxe'=>'Saskatoon',
        'yqr'=>'Regina'
    ],
];
$attribs = [
    'class'=>'input-select',
    'placeholder'=>'Select a city' // the first, no value item (optional)
];
echo $this->select($attribs, $options, ['yyc']);
?>
```

**Output**
    
    <select class="input-select">
        <option value="">Select a city</option>
        <optgroup label="AB">
            <option value="yeg">Edmonton</option>
            <option value="yyc" selected="selected">Calgary</option>
        </optgroup>
        <optgroup label="SK">
            <option value="yxe">Saskatoon</option>
            <option value="yqr">Regina</option>
        </optgroup>
    </select>

**Example 3**

```php
<?php
echo $this->select(['class'=>'input-select'])
          ->optgroup('AB')
          ->option('yeg', 'Edmonton')
          ->option('yyc', 'Calgary')
          ->optgroup('SK')
          ->option('yxe', 'Saskatoon')
          ->option('yqr', 'Regina')
          ->selected(['yyc'])
          ->fetch();
?>
```

**Output**

    <select class="input-select">
        <optgroup label="AB">
            <option value="yeg">Edmonton</option>
            <option value="yyc" selected="selected">Calgary</option>
        </optgroup>
        <optgroup label="SK">
            <option value="yxe">Saskatoon</option>
            <option value="yqr">Regina</option>
        </optgroup>
    </select>

-----

Textarea
-----

Returns a textarea tag

**Syntax**

```php
<?php echo $this->textarea( array $attribs [, $value = null ] ); ?>
```

**Parameters

    @param $attribs: Array of attributes for the textarea tag
    @param $value: The value of the textarea tag
    
**Example**

```php
<?php echo $this->textarea(['class'=>'input-textarea'], 'Here is some text'); ?>
```

**Output**

    <textarea class="input-textarea">Here is some text</textarea>
    
-----

