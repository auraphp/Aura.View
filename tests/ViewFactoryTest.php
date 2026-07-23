<?php
declare(strict_types=1);

namespace Aura\View;

use PHPUnit\Framework\TestCase;

class ViewFactoryTest extends TestCase
{
    protected ViewFactory $view_factory;

    protected function setUp(): void
    {
        $this->view_factory = new ViewFactory;
    }

    public function testNewInstanceWithNoArguments(): void
    {
        $view = $this->view_factory->newInstance();

        $this->assertInstanceOf(View::class, $view);
        $this->assertInstanceOf(HelperRegistry::class, $view->getHelpers());
        $this->assertSame([], $view->getViewRegistry()->getPaths());
        $this->assertSame([], $view->getLayoutRegistry()->getPaths());
    }

    /**
     * The one-line Aura.Html seam preserved in PR 3, and the form shown in
     * docs/helpers.md. $helpers stays the first parameter so this keeps
     * working as a positional call.
     */
    public function testHelpersOnlyPositionalCallStillWorks(): void
    {
        $helpers = new HelperRegistry(['hello' => fn (string $n): string => "Hello {$n}!"]);

        $view = $this->view_factory->newInstance($helpers);

        $this->assertSame($helpers, $view->getHelpers());
    }

    /**
     * The 2.x five-argument positional form is the one call shape this change
     * breaks, and it breaks loudly rather than silently.
     */
    public function testLegacyFiveArgumentPositionalCallRaisesTypeError(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument #2 \(\$view\) must be of type \?Aura\\\\View\\\\ViewSpec/');

        /** @phpstan-ignore-next-line argument.type */
        $this->view_factory->newInstance(
            new HelperRegistry(),
            ['browse' => '/path/to/views/browse.php'],
            ['/path/to/views'],
            ['default' => '/path/to/layouts/default.php'],
            ['/path/to/layouts']
        );
    }

    public function testMapAndPathsGoToTheRightRegistry(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(
                map: ['browse' => __DIR__ . '/foo_template.php'],
                paths: ['/path/to/views'],
            ),
            layout: new ViewSpec(
                map: ['default' => __DIR__ . '/foo_template.php'],
                paths: ['/path/to/layouts'],
            ),
        );

        $this->assertTrue($view->getViewRegistry()->has('browse'));
        $this->assertFalse($view->getViewRegistry()->has('default'));
        $this->assertSame(['/path/to/views'], $view->getViewRegistry()->getPaths());

        $this->assertTrue($view->getLayoutRegistry()->has('default'));
        $this->assertFalse($view->getLayoutRegistry()->has('browse'));
        $this->assertSame(['/path/to/layouts'], $view->getLayoutRegistry()->getPaths());
    }

    // -----------------------------------------------------------------------
    // The defect this PR fixes: in 2.x the factory built each TemplateRegistry
    // with two of its three constructor arguments, dropping $namespaces
    // entirely -- so namespaced templates were unreachable through the factory
    // even though TemplateRegistry has supported them since 2.4.0.
    // -----------------------------------------------------------------------

    public function testNamespacesArePlumbedThrough(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(namespaces: ['blog' => [__DIR__]]),
            layout: new ViewSpec(namespaces: ['site' => [__DIR__]]),
        );

        $this->assertTrue($view->getViewRegistry()->hasNamespace('blog'));
        $this->assertTrue($view->getLayoutRegistry()->hasNamespace('site'));
    }

    public function testViewAndLayoutNamespacesDoNotCross(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(namespaces: ['blog' => [__DIR__]]),
            layout: new ViewSpec(namespaces: ['site' => [__DIR__]]),
        );

        $this->assertFalse($view->getViewRegistry()->hasNamespace('site'));
        $this->assertFalse($view->getLayoutRegistry()->hasNamespace('blog'));
    }

    public function testNamespacedTemplateRendersThroughTheFactory(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(namespaces: ['blog' => [__DIR__]]),
        );

        $view->setView('blog::foo_template');

        $this->assertSame('Hello Foo!', $view->__invoke());
    }

    public function testNamespacedLayoutRendersThroughTheFactory(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(map: ['index' => __DIR__ . '/foo_template.php']),
            layout: new ViewSpec(namespaces: ['site' => [__DIR__]]),
        );

        $view->setView('index');
        $view->setLayout('site::foo_template');

        // the layout ignores the content and just says "Hello Foo!"
        $this->assertSame('Hello Foo!', $view->__invoke());
    }

    /**
     * The factory is a convenience, not a second code path: namespaces set
     * through it must behave identically to namespaces set on the registry.
     */
    public function testFactoryNamespacesMatchRegistryNamespaces(): void
    {
        $viaFactory = $this->view_factory->newInstance(
            view: new ViewSpec(namespaces: ['blog' => [__DIR__]]),
        );

        $viaRegistry = $this->view_factory->newInstance();
        $viaRegistry->getViewRegistry()->appendPath(__DIR__, 'blog');

        $viaFactory->setView('blog::foo_template');
        $viaRegistry->setView('blog::foo_template');

        $this->assertSame($viaFactory->__invoke(), $viaRegistry->__invoke());
    }

    // -----------------------------------------------------------------------
    // The template file extension, previously unreachable through the factory
    // for the same reason namespaces were.
    // -----------------------------------------------------------------------

    public function testExtensionDefaultsToPhp(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(paths: [__DIR__]),
        );

        $view->setView('foo_template');

        $this->assertSame('Hello Foo!', $view->__invoke());
    }

    public function testExtensionIsPlumbedThrough(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(paths: [__DIR__ . '/fixtures'], extension: '.phtml'),
        );

        $view->setView('bar_template');

        $this->assertSame('Hello Bar!', $view->__invoke());
    }

    public function testViewAndLayoutExtensionsAreIndependent(): void
    {
        $view = $this->view_factory->newInstance(
            view: new ViewSpec(paths: [__DIR__ . '/fixtures'], extension: '.phtml'),
            layout: new ViewSpec(paths: [__DIR__]),
        );

        $view->setView('bar_template');
        $view->setLayout('foo_template');

        // the layout ignores the content and just says "Hello Foo!"
        $this->assertSame('Hello Foo!', $view->__invoke());
    }

    // -----------------------------------------------------------------------
    // The spec object itself.
    // -----------------------------------------------------------------------

    public function testSpecDefaults(): void
    {
        $spec = new ViewSpec();

        $this->assertSame([], $spec->map);
        $this->assertSame([], $spec->paths);
        $this->assertSame([], $spec->namespaces);
        $this->assertSame('.php', $spec->extension);
    }

    public function testSpecIsReadonly(): void
    {
        $spec = new ViewSpec(paths: ['/path/to/views']);

        $this->expectException(\Error::class);
        /** @phpstan-ignore-next-line assign.propertyReadOnly */
        $spec->paths = ['/somewhere/else'];
    }

    public function testSpecBuildsItsOwnRegistry(): void
    {
        $spec = new ViewSpec(
            map: ['browse' => __DIR__ . '/foo_template.php'],
            paths: ['/path/to/views'],
            namespaces: ['blog' => [__DIR__]],
        );

        $registry = $spec->newRegistry();

        $this->assertInstanceOf(TemplateRegistry::class, $registry);
        $this->assertTrue($registry->has('browse'));
        $this->assertSame(['/path/to/views'], $registry->getPaths());
        $this->assertTrue($registry->hasNamespace('blog'));
    }
}
