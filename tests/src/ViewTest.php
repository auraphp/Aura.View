<?php
namespace Aura\View;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    protected $view;

    protected function setUp()
    {
        $view_factory = new ViewFactory;
        $this->view = $view_factory->newInstance();

        $helpers = $this->view->getHelpers();
        $helpers->set('hello', function ($name) {
            return "Hello {$name}!";
        });

        $view_registry = $this->view->getViewRegistry();
        $view_registry->set('index', function () {
            echo $this->hello($this->name);
        });

        $layout_registry = $this->view->getLayoutRegistry();
        $layout_registry->set('default', function () {
            echo "before -- {$this->content} -- after";
        });
    }

    public function testInvalidHelpersObject()
    {
        $this->setExpectedException('Aura\View\Exception\InvalidHelpersObject');
        new View(new TemplateRegistry, new TemplateRegistry, 'invalid');
    }

    public function testMagicMethods()
    {
        $this->assertFalse(isset($this->view->foo));

        $this->view->foo = 'bar';
        $this->assertTrue(isset($this->view->foo));
        $this->assertSame('bar', $this->view->foo);

        unset($this->view->foo);
        $this->assertFalse(isset($this->view->foo));

        $actual = $this->view->hello('Helper');
        $this->assertSame('Hello Helper!', $actual);
    }

    public function testGetters()
    {
        $this->assertInstanceOf('Aura\View\TemplateRegistry', $this->view->getViewRegistry());
        $this->assertInstanceOf('Aura\View\TemplateRegistry', $this->view->getLayoutRegistry());
        $this->assertInstanceOf('Aura\View\HelperRegistry', $this->view->getHelpers());
    }

    public function testSetAndGetContentVar()
    {
        $this->assertSame('content', $this->view->getContentVar());
        $this->view->setContentVar('content_for_layout');
        $this->assertSame('content_for_layout', $this->view->getContentVar());
    }

    public function testSetAndGetData()
    {
        $data = array('foo' => 'bar');
        $this->view->setData($data);
        $this->assertSame('bar', $this->view->foo);

        $actual = (array) $this->view->getData();
        $this->assertSame($data, $actual);
    }

    public function testInvokeOneStep()
    {
        $this->view->setData(array('name' => 'Index'));
        $this->view->setView('index');
        $actual = $this->view->__invoke();
        $expect = "Hello Index!";
        $this->assertSame($expect, $actual);
    }

    public function testInvokeTwoStep()
    {
        $this->view->setData(array('name' => 'Index'));
        $this->view->setView('index');
        $this->view->setLayout('default');
        $actual = $this->view->__invoke();
        $expect = "before -- Hello Index! -- after";
        $this->assertSame($expect, $actual);
    }

    public function testPartials()
    {
        // add templates to the view registry
        $view_registry = $this->view->getViewRegistry();

        $view_registry->set('item_rows', function () {
            foreach ($this->items as $item) {
                echo $this->render('item_row', array('item' => $item));
            };
        });

        $view_registry->set('item_row', function () {
            echo $item['name']  . ' costs ' . $item['price'] . PHP_EOL;
        });

        // set the data and the view template name
        $this->view->setData(array(
            'items' => array(
                array(
                    'name' => 'A',
                    'price' => 20
                ),
                array(
                    'name' => 'B',
                    'price' => 20
                ),
                array(
                    'name' => 'C',
                    'price' => 20
                )
            )
        ));
        $this->view->setView('item_rows');

        // execute the view
        $output = $this->view->__invoke();
        $this->assertNotEmpty($output);
    }
}
