<?php
namespace Aura\View;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    protected $finder;
    
    protected function setUp()
    {
        $this->finder = new MockFinder;
    }
    
    public function testPaths()
    {
        $expect = [
            '\Foo\\' => '/path/to/Foo/',
            '\Foo\Bar\\' => '/path/to/Foo/Bar/',
            '\Foo\Bar\Baz\\' => '/path/to/Foo/Bar/Baz/',
        ];
        
        $this->finder->setPaths($expect);
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
        
        // test normalization
        $this->finder->setPaths(['Foo' => '/path/to/Foo']);
        $expect = ['\Foo\\' => '/path/to/Foo/'];
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
    }
    
    public function testNames()
    {
        $names = [
            '\browse' => function () { echo 'browse'; },
            '\read'   => function () { echo 'read'; },
            '\edit'   => function () { echo 'edit'; },
            '\add'    => function () { echo 'add'; },
            '\delete' => function () { echo 'delete'; },
        ];
        $this->finder->setNames($names);
        $this->assertSame($names, $this->finder->getNames());
        
        // test the normalization
        $this->finder->setNames(['search' => '/path/to/search.php']);
        $expect = ['\search' => '/path/to/search.php'];
        $actual = $this->finder->getNames();
        $this->assertSame($expect, $actual);
    }
    
    public function testPrefixes()
    {
        $expect = [
            '\Foo\Bar\Baz\\',
            '\Foo\Bar\\',
            '\Foo\\',
        ];
        
        $this->finder->setPrefixes($expect);
        $actual = $this->finder->getPrefixes();
        $this->assertSame($expect, $actual);
        
        // test the normalization
        $this->finder->setPrefixes(['Foo\Bar\Baz\Qux']);
        $expect = ['\Foo\Bar\Baz\Qux\\'];
        $actual = $this->finder->getPrefixes();
        $this->assertSame($expect, $actual);
    }
    
    public function testFind()
    {
        // fake a file system
        $this->finder->is_readable = [
            '/path/to/foo-bar/src/Quux.php',
            '/path/to/Aura.View/src/Quux.php',
            '/path/to/vendor/baz/qux/src/Quux.php',
        ];
        
        // set the paths
        $this->finder->setPaths([
            'Foo\Bar' => '/path/to/foo-bar/src',
            'Aura\View' => '/path/to/Aura.View/src',
            'Baz\Qux' => '/path/to/vendor/baz/qux/src',
        ]);
        
        // set the prefixes
        $this->finder->setPrefixes([
            'Foo\Bar',
            'Aura\View',
            'Baz\Qux',
        ]);
        
        // add a relative template by name
        $this->finder->setName('\Baz\Qux\Relative', function () {
            return 'Relative';
        });
        
        // add an absolute template by name
        $this->finder->setName('\Absolute', function () {
            return 'Absolute';
        });
    
        // find the relative template
        $template = $this->finder->find('Relative');
        $expect = 'Relative';
        $actual = $template();
        $this->assertSame($expect, $actual);
        
        // find it again for code coverage
        $template = $this->finder->find('Relative');
        $expect = 'Relative';
        $actual = $template();
        $this->assertSame($expect, $actual);
        
        // find as an absolute location
        $template = $this->finder->find('Absolute');
        $expect = 'Absolute';
        $actual = $template();
        $this->assertSame($expect, $actual);
        
        // nothing there
        $this->assertFalse($this->finder->find('no_such_template'));
        
        // find from fake file system
        $actual = $this->finder->find('Quux');
        $expect = '/path/to/foo-bar/src/Quux.php';
        $this->assertSame($expect, $actual);
    }
}
