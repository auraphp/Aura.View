<?php
namespace Aura\View;

class TemplateRegistryTest extends \PHPUnit_Framework_TestCase
{
    protected $template_registry;

    protected function setUp()
    {
        $this->template_registry = new TemplateRegistry;
    }

    public function testSetHasGet()
    {
        $foo = function () {
            return "Foo!";
        };

        $this->assertFalse($this->template_registry->has('foo'));

        $this->template_registry->set('foo', $foo);
        $this->assertTrue($this->template_registry->has('foo'));

        $template = $this->template_registry->get('foo');
        $this->assertSame($foo, $template);

        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $this->template_registry->get('bar');
    }

    public function testSetString()
    {
        $this->template_registry->set('foo', __DIR__ . '/foo_template.php');
        $template = $this->template_registry->get('foo');
        $this->assertInstanceOf('Closure', $template);

        ob_start();
        $template();
        $actual = ob_get_clean();
        $expect = 'Hello Foo!';
        $this->assertSame($expect, $actual);
    }
}
