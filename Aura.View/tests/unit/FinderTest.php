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
            'Foo\Bar\Baz' => '/path/to/Foo/Bar/Baz',
            'Foo\Bar' => '/path/to/Foo/Bar',
            'Foo' => '/path/to/Foo',
        ];
        
        $this->finder->setPaths($expect);
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
    }
    
    public function testNames()
    {
        // set the names
        $names = [
            'browse' => function () { echo 'browse'; },
            'read'   => function () { echo 'read'; },
            'edit'   => function () { echo 'edit'; },
            'add'    => function () { echo 'add'; },
            'delete' => function () { echo 'delete'; },
        ];
        $this->finder->setNames($names);
        $this->assertSame($names, $this->finder->getNames());
    }
    
    public function testFind()
    {
        // set the paths
        $this->finder->setPaths([
            'Foo\Bar',
            'Aura\View',
            'Baz\Qux',
        ]);
        
        // add a relative name
        $relative =  function () {
            echo 'Baz Qux';
        };
        $this->finder->setName('Baz\Qux\MockTemplate', $relative);
        
        // add an absolute name
        $absolute = function () {
            echo 'Absolute';
        };
        $this->finder->setName('AbsoluteTemplate', $absolute);
        
        // now find the MockTemplate as a class
        $expect = 'Aura\View\MockTemplate';
        $actual = $this->finder->find('MockTemplate');
        $this->assertSame($expect, $actual);
        
        // find it again for code coverage
        $actual = $this->finder->find('MockTemplate');
        $this->assertSame($expect, $actual);
        
        // find as a relative location
        $actual = $this->finder->find('Baz\Qux\MockTemplate');
        $this->assertSame($relative, $actual);
        
        // find as an absolute name
        $actual = $this->finder->find('AbsoluteTemplate');
        $this->assertSame($absolute, $actual);
        
        // nothing there
        $this->assertFalse($this->finder->find('no_such_template'));
    }
}
