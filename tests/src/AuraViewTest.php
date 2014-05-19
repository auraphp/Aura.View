<?php
namespace Aura\View;

use Aura\View\ViewFactory;

class AuraViewTest extends \PHPUnit_Framework_TestCase
{
    protected $engine;

    public function setUp()
    {
        $factory = new ViewFactory();
        $view = $factory->newInstance();
        $this->engine = new AuraView($view);
        $view_registry = $this->engine->getEngine()->getViewRegistry();

        $view_registry->set('hello', function () {
            echo "Hello {$this->name}!";
        });

        $view_registry->set('main', function () {
            echo "This is the main content." . PHP_EOL;
        });

        $layout_registry = $this->engine->getEngine()->getLayoutRegistry();
        $layout_registry->set('wrapper', function () {
            echo "Before the main content." . PHP_EOL;
            echo $this->getContent();
            echo "After the main content." . PHP_EOL;
        });
    }

    public function testRenderHello()
    {
        $data = array(
            'name' => 'Aura'
        );
        $output = $this->engine->render($data, 'hello');
        $this->assertNotEmpty($output);
    }

    public function testRenderWithoutLayout()
    {
        $data = array();
        $this->assertNotEmpty($this->engine->render($data, 'main', 'wrapper'));
    }
}
