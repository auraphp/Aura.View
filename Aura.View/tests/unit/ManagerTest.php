<?php
namespace Aura\View;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->view_finder = new Finder;
        $this->view_finder->setClosure('IndexView', function () {
            echo '<p>Hello ' . $this->noun . '!</p>';
        });
        
        $this->layout_finder = new Finder;
        $this->layout_finder->setClosure('DefaultLayout', function () {
            echo '<html>'
               . '<head><title>Test</title></head>'
               . '<body>' . $this->content . '</body>'
               . '</html>';
        });
        
        $this->factory = new Factory;
        $this->helper = new Helper;
        
        $this->manager = new Manager(
            $this->factory,
            $this->helper,
            $this->view_finder,
            $this->layout_finder
        );
    }
    
    public function testGetters()
    {
        $this->assertSame($this->factory, $this->manager->getFactory());
        $this->assertSame($this->helper, $this->manager->getHelper());
        $this->assertSame($this->view_finder, $this->manager->getViewFinder());
        $this->assertSame($this->layout_finder, $this->manager->getLayoutFinder());
        $this->assertSame('content', $this->manager->getContentVar());
    }
    
    public function testSContentVar()
    {
        $this->manager->setContentVar('layout_content');
        $this->assertSame('layout_content', $this->manager->getContentVar());
    }
    
    public function testRenderView()
    {
        $data = ['noun' => 'World'];
        $actual = $this->manager->render($data, 'IndexView');
        $expect = '<p>Hello World!</p>';
        $this->assertSame($expect, $actual);
    }
    
    public function testRenderViewAndLayout()
    {
        $data = ['noun' => 'World'];
        $actual = $this->manager->render($data, 'IndexView', 'DefaultLayout');
        $expect = '<html>'
                . '<head><title>Test</title></head>'
                . '<body><p>Hello World!</p></body>'
                . '</html>';
        $this->assertSame($expect, $actual);
    }
    
    
}
