<?php
namespace Aura\View;

class TemplateRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testSetHasGet()
    {
        $template_registry = new TemplateRegistry;

        $foo = function () {
            return "Foo!";
        };

        $this->assertFalse($template_registry->has('foo'));

        $template_registry->set('foo', $foo);
        $this->assertTrue($template_registry->has('foo'));

        $template = $template_registry->get('foo');
        $this->assertSame($foo, $template);

        $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
        $template_registry->get('bar');
    }

    public function testSetString()
    {
        $template_registry = new TemplateRegistry;

        $template_registry->set('foo', __DIR__ . '/foo_template.php');
        $template = $template_registry->get('foo');
        $this->assertInstanceOf('Closure', $template);

        ob_start();
        $template();
        $actual = ob_get_clean();
        $expect = 'Hello Foo!';
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetPaths()
    {
        $template_registry = new TemplateRegistry;

        // should be no paths yet
        $expect = array();
        $actual = $template_registry->getPaths();
        $this->assertSame($expect, $actual);

        // set the paths
        $expect = array('/foo', '/bar', '/baz');
        $template_registry->setPaths($expect);
        $actual = $template_registry->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testSearch()
    {
        $template_registry = new TemplateRegistry;
        $template_registry->fakefs = array(
            '/foo',
            '/bar',
            '/baz'
        );
    }
}
