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
        
        $this->template = new Template;
        
        $this->helper = new Helper;
        
        $this->view_finder = new Finder;
        $this->view_finder->setName('IndexView', function () {
            echo '<p>Hello ' . $this->noun . '!</p>';
        });
        
        $this->layout_finder = new Finder;
        $this->layout_finder->setName('DefaultLayout', function () {
            echo '<html>'
               . '<head><title>Test</title></head>'
               . '<body>' . $this->content . '</body>'
               . '</html>';
        });
        
        $this->manager = new Manager(
            $this->template,
            $this->helper,
            $this->view_finder,
            $this->layout_finder
        );
    }
    
    public function testGetters()
    {
        $this->assertSame($this->template, $this->manager->getTemplate());
        $this->assertSame($this->view_finder, $this->manager->getViewFinder());
        $this->assertSame($this->layout_finder, $this->manager->getLayoutFinder());
        $this->assertSame('content', $this->manager->getContentVar());
    }
    
    public function testContentVar()
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
    
    public function testRenderView_templateNotFound()
    {
        $data = ['noun' => 'World'];
        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $actual = $this->manager->render($data, 'no-such-template');
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
