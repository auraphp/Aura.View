<?php
declare(strict_types=1);

namespace Aura\View;

use Aura\Html\Escaper;
use Aura\Html\HelperLocatorFactory;
use PHPUnit\Framework\TestCase;

/**
 * Aura.View takes no runtime dependency on Aura.Html; this test exists to keep
 * the documented integration seam honest.
 *
 * The seam is that _ViewFactory::newInstance()_ types its helper manager as
 * `?object`, not as HelperRegistryInterface. Aura.Html's _HelperLocator_
 * cannot implement an Aura.View interface without depending on Aura.View --
 * the wrong direction -- so narrowing that parameter would silently break this
 * usage. If someone narrows it, this test fails.
 */
class AuraHtmlIntegrationTest extends TestCase
{
    public function testHelperLocatorWorksAsAHelperManagerWithoutAnAdapter(): void
    {
        $helpers = (new HelperLocatorFactory())->newInstance();

        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance($helpers);

        $this->assertSame($helpers, $view->getHelpers());

        $view->getViewRegistry()->set('index', function () {
            echo $this->a('http://example.com', 'Example');
        });
        $view->setView('index');

        $this->assertSame(
            '<a href="http://example.com">Example</a>',
            $view->__invoke()
        );
    }

    /**
     * Aura.View ships no escaper, so escaping in a template is explicit. This
     * covers the route the README documents: Aura.Html's _Escaper_ statics,
     * which HelperLocatorFactory wires up via Escaper::setStatic().
     */
    public function testEscapingInATemplateViaTheAuraHtmlEscaper(): void
    {
        $helpers = (new HelperLocatorFactory())->newInstance();

        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance($helpers);

        $view->setData(['name' => '<script>']);
        $view->getViewRegistry()->set('index', function () {
            echo Escaper::h($this->name);
        });
        $view->setView('index');

        $this->assertSame('&lt;script&gt;', $view->__invoke());
    }

    public function testInputHelpersEscapeThroughTheSameSeam(): void
    {
        $helpers = (new HelperLocatorFactory())->newInstance();

        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance($helpers);

        $view->setData(['value' => '<b>']);
        $view->getViewRegistry()->set('index', function () {
            echo $this->input([
                'type' => 'text',
                'name' => 'foo',
                'value' => $this->value,
            ]);
        });
        $view->setView('index');

        $this->assertSame(
            '<input type="text" name="foo" value="&lt;b&gt;" />' . PHP_EOL,
            $view->__invoke()
        );
    }
}
