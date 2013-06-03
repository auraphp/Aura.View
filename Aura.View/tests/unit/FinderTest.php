<?php
namespace Aura\View;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    protected $finder;

    protected function setUp()
    {
        $this->finder = new Finder;
    }
    
    public function testPrefixes()
    {
        // should be no prefixes yet
        $expect = [];
        $actual = $this->finder->getPrefixes();
        $this->assertSame($expect, $actual);
        
        // set the prefixes
        $expect = [
            'Foo\Bar\Baz',
            'Foo\Bar',
            'Foo',
        ];
        $this->finder->setPrefixes($expect);
        $actual = $this->finder->getPrefixes();
        $this->assertSame($expect, $actual);
        
        // off the top
        $actual = $this->finder->shiftPrefix();
        $this->assertSame('Foo\Bar\Baz', $actual);
        
        // off the end
        $actual = $this->finder->popPrefix();
        $this->assertSame('Foo', $actual);
        
        // onto top
        $this->finder->unshiftPrefix('Qux');
        $this->assertSame(['Qux', 'Foo\Bar'], $this->finder->getPrefixes());
        
        // onto end
        $this->finder->pushPrefix('Quux');
        $this->assertSame(['Qux', 'Foo\Bar', 'Quux'], $this->finder->getPrefixes());
        
    }
    
    public function testClosures()
    {
        // should be no closures yet
        $expect = [];
        $actual = $this->finder->getClosures();
        $this->assertSame($expect, $actual);
        
        // set the closures
        $closures = [
            'browse' => function () { echo 'browse'; },
            'read'   => function () { echo 'read'; },
            'edit'   => function () { echo 'edit'; },
            'add'    => function () { echo 'add'; },
            'delete' => function () { echo 'delete'; },
        ];
        $this->finder->setClosures($closures);
        $this->assertSame($closures, $this->finder->getClosures());
    }
    
    /**
     * @todo Implement testFind().
     */
    public function testFind()
    {
        // set the prefixes
        $this->finder->setPrefixes([
            'Foo\Bar',
            'Aura\View',
            'Baz\Qux',
        ]);
        
        // add a relative closure
        $relative =  function () {
            echo 'Baz Qux';
        };
        $this->finder->setClosure('Baz\Qux\MockTemplate', $relative);
        
        // add an absolute closure
        $absolute = function () {
            echo 'Absolute';
        };
        $this->finder->setClosure('AbsoluteTemplate', $absolute);
        
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
        
        // find as an absolute closure
        $actual = $this->finder->find('AbsoluteTemplate');
        $this->assertSame($absolute, $actual);
        
        // nothing there
        $this->assertFalse($this->finder->find('no_such_template'));
    }
}
