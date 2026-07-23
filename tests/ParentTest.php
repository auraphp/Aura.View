<?php
declare(strict_types=1);

namespace Aura\View;

use PHPUnit\Framework\TestCase;

/**
 * Covers parent(): rendering the template that the currently-executing one
 * shadows, by resuming the search path after the directory it was found in.
 */
class ParentTest extends TestCase
{
    protected View $view;

    protected function setUp(): void
    {
        $this->view = (new ViewFactory)->newInstance();

        // /app shadows /module shadows /core, the module cascade in miniature
        $registry = $this->view->getViewRegistry();
        $registry->setPaths([
            __DIR__ . '/fixtures/parent/app',
            __DIR__ . '/fixtures/parent/module',
            __DIR__ . '/fixtures/parent/core',
        ]);
    }

    protected function invoke(string $name): string
    {
        $this->view->setView($name);
        return ($this->view)();
    }

    public function testParentWalksTheWholeChain()
    {
        // three deep: each template wraps the one it shadows
        $this->assertSame('app(module(core))', $this->invoke('read'));
    }

    public function testParentAtTheEndOfTheChainReturnsEmptyString()
    {
        // 'orphan' exists only in /app, so it shadows nothing. That is a
        // normal state during development, not an error.
        $this->assertSame('orphan()', $this->invoke('orphan'));
    }

    public function testParentPassesVarsToTheShadowedTemplate()
    {
        $this->assertSame('app(core:hi)', $this->invoke('vars'));
    }

    public function testParentResolvesTheInnerNameDuringANestedRender()
    {
        // 'outer' renders 'read' partway through, then calls its own parent().
        // The inner render must not leave the stack pointing at 'read'.
        $this->assertSame('outer[app(module(core))]', $this->invoke('outer'));
    }

    public function testParentIsPerNameNotPerPath()
    {
        // rendering 'read' twice in a row must give the same answer; the
        // stack has to unwind fully between them
        $this->assertSame('app(module(core))', $this->invoke('read'));
        $this->assertSame('app(module(core))', $this->invoke('read'));
    }

    public function testParentWorksWhenPathsHaveTrailingSeparators()
    {
        // a trailing slash is a spelling of the same directory, not a
        // different one; parent() must not go quiet because of it
        $this->view->getViewRegistry()->setPaths([
            __DIR__ . '/fixtures/parent/app/',
            __DIR__ . '/fixtures/parent/module/',
            __DIR__ . '/fixtures/parent/core/',
        ]);

        $this->assertSame('app(module(core))', $this->invoke('read'));
    }

    public function testParentReturnsEmptyStringForAMappedTemplate()
    {
        // an explicit map entry has no path behind it, so there is nothing to
        // resume from
        $registry = $this->view->getViewRegistry();
        $registry->set('mapped', __DIR__ . '/fixtures/parent/app/orphan.php');

        $this->assertSame('orphan()', $this->invoke('mapped'));
    }

    public function testParentWalksNamespacedPaths()
    {
        $registry = $this->view->getViewRegistry();
        $registry->appendPath(__DIR__ . '/fixtures/parent/ns-app', 'ns');
        $registry->appendPath(__DIR__ . '/fixtures/parent/ns-core', 'ns');

        $this->assertSame('ns-app(ns-core)', $this->invoke('ns::read'));
    }

    public function testTheRenderStackUnwindsWhenATemplateThrows()
    {
        $this->view->setView('boom');

        try {
            ($this->view)();
            $this->fail('Expected RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertSame('boom', $e->getMessage());
        }

        // the failed render must not have left a frame behind
        $this->assertSame('app(module(core))', $this->invoke('read'));
    }

    public function testParentOutsideOfARenderThrows()
    {
        $view = new class ((new ViewFactory)->newInstance()->getViewRegistry(), new TemplateRegistry) extends View {
            public function callParent(): string
            {
                return $this->parent();
            }
        };

        $this->expectException('Aura\View\Exception');
        $view->callParent();
    }

    public function testParentThrowsWhenASubclassOverridesRenderWithoutTheStack()
    {
        // the failure this guard exists for: a subclass reimplements render()
        // and forgets pushRender()/popRender(). Without the throw, every
        // parent() in the application would quietly return '' and every
        // override would silently go back to replacing instead of extending.
        $registry = $this->view->getViewRegistry();

        $view = new class ($registry, new TemplateRegistry) extends View {
            protected function render(string $name, array $vars = []): string
            {
                return $this->captureTemplate($this->getTemplate($name), $vars);
            }
        };

        $view->setView('read');

        $this->expectException('Aura\View\Exception');
        $view();
    }

    public function testParentIsEmptyWhenTheRegistryHasNoSearchPaths()
    {
        // a registry backed by a precompiled name-to-file map has no paths to
        // resume along; parent() degrades to '' rather than fataling
        $registry = new FakeRegistryWithoutPaths;
        $registry->set('read', __DIR__ . '/fixtures/parent/app/read.php');

        $view = new View($registry, new TemplateRegistry);
        $view->setView('read');

        $this->assertSame('app()', $view());
    }
}
