<?php
declare(strict_types=1);

namespace Aura\View;

use PHPUnit\Framework\TestCase;

class TemplateRegistryTest extends TestCase
{
    protected TemplateRegistry $template_registry;

    protected function setUp(): void
    {
        $this->template_registry = new TemplateRegistry;
    }

    /**
     * FakeTemplateRegistry encloses a found file in a closure that echoes the
     * file name, so invoking the template reveals which file the search picked.
     */
    protected function assertResolvesTo(string $expect, string $name): void
    {
        ob_start();
        $this->template_registry->get($name)();
        $this->assertSame($expect, ob_get_clean());
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

        $this->expectException('Aura\View\Exception\TemplateNotFound');
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

    public function testSetAndGetPaths()
    {
        // should be no paths yet
        $expect = array();
        $actual = $this->template_registry->getPaths();
        $this->assertSame($expect, $actual);

        // set the paths
        $expect = array('/foo', '/bar', '/baz');
        $this->template_registry->setPaths($expect);
        $actual = $this->template_registry->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testPrependPath()
    {
        $this->template_registry->prependPath('/foo');
        $this->template_registry->prependPath('/bar');
        $this->template_registry->prependPath('/baz');

        $expect = array('/baz', '/bar', '/foo');
        $actual = $this->template_registry->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testAppendPath()
    {
        $this->template_registry->appendPath('/foo');
        $this->template_registry->appendPath('/bar');
        $this->template_registry->appendPath('/baz');

        $expect = array('/foo', '/bar', '/baz');
        $actual = $this->template_registry->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testSearch()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->template_registry->appendPath('/foo');
        $this->template_registry->appendPath('/bar');
        $this->template_registry->appendPath('/baz');

        // place a file in one of the paths at random
        $paths = array('/foo', '/bar', '/baz');
        $key = array_rand($paths);
        $path = $paths[$key];
        $file = $path . DIRECTORY_SEPARATOR . 'zim.php';
        $this->template_registry->fakefs[$file] = 'fake';

        // now get it
        $this->assertResolvesTo($file, 'zim');

        // get it again for code coverage
        $this->assertResolvesTo($file, 'zim');


        // test searching with a non-default template file extension
        $this->template_registry = new FakeTemplateRegistry;
        $this->template_registry->appendPath('/foo');
        $this->template_registry->setTemplateFileExtension('.phtml');
        $file = "/foo" . DIRECTORY_SEPARATOR . 'test.phtml';
        $this->template_registry->fakefs[$file] = 'fake';

        $this->assertResolvesTo($file, 'test');

        // look for a file that doesn't exist
        $this->expectException('Aura\View\Exception\TemplateNotFound');
        $actual = $this->template_registry->get('no-such-template');
    }

    public function testFindNamespaced()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->template_registry->appendPath('/foo');
        $this->template_registry->appendPath('/bar', 'ns');

        $file = '/bar' . DIRECTORY_SEPARATOR . 'zim.php';
        $this->template_registry->fakefs[$file] = 'fake';

        $this->assertResolvesTo($file, 'ns::zim');

        // prepend
        $this->template_registry->prependPath('/bar', 'ns2');
        $this->template_registry->prependPath('/baz', 'ns2');

        $wrong = '/bar' . DIRECTORY_SEPARATOR . 'zim.php';
        $this->template_registry->fakefs[$wrong] = 'wrong';

        $file = '/baz' . DIRECTORY_SEPARATOR . 'zim.php';
        $this->template_registry->fakefs[$file] = 'new';

        $this->assertResolvesTo($file, 'ns2::zim');


        // doesnt exist
        $this->expectException('Aura\View\Exception\TemplateNotFound');
        $actual = $this->template_registry->get('ns::zam');
    }

    public function testUnregisteredNs()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->expectException('Aura\View\Exception\TemplateNotFound');
        $actual = $this->template_registry->get('ns::no-exist');
    }

    public function testBadNamespace()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->expectException('Aura\View\Exception\InvalidTemplateName');
        $actual = $this->template_registry->get('ns::wrong::format');
    }

    public function testSetTemplateFileExtensionResetsFound()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->template_registry->appendPath('/foo');

        $php = '/foo' . DIRECTORY_SEPARATOR . 'zim.php';
        $phtml = '/foo' . DIRECTORY_SEPARATOR . 'zim.phtml';
        $this->template_registry->fakefs[$php] = 'fake';
        $this->template_registry->fakefs[$phtml] = 'fake';

        // resolve once under the default extension ...
        $this->assertResolvesTo($php, 'zim');

        // ... then change the extension; the memoized hit must not survive.
        $this->template_registry->setTemplateFileExtension('.phtml');
        $this->assertResolvesTo($phtml, 'zim');
    }

    /**
     * Builds a registry where 'zim' exists in three stacked paths, so that
     * '/first' shadows '/second' shadows '/third'.
     */
    protected function newShadowedRegistry(): FakeTemplateRegistry
    {
        $registry = new FakeTemplateRegistry;
        $registry->appendPath('/first');
        $registry->appendPath('/second');
        $registry->appendPath('/third');

        foreach (['/first', '/second', '/third'] as $path) {
            $registry->fakefs[$path . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        }

        return $registry;
    }

    public function testGetResolvedPathReportsTheWinningDirectory()
    {
        $registry = $this->newShadowedRegistry();
        $this->template_registry = $registry;

        $this->assertResolvesTo('/first' . DIRECTORY_SEPARATOR . 'zim.php', 'zim');
        $this->assertSame('/first', $registry->getResolvedPath('zim'));
    }

    public function testGetResolvedPathIsNullForAMappedTemplate()
    {
        // an explicit map entry has no search path behind it
        $registry = new FakeTemplateRegistry;
        $registry->set('zim', '/wherever/zim.php');

        $this->assertNull($registry->getResolvedPath('zim'));
    }

    public function testGetResolvedPathIsNullForAnUnresolvableName()
    {
        $registry = new FakeTemplateRegistry;
        $this->assertNull($registry->getResolvedPath('no-such-template'));
    }

    public function testGetNextWalksTheChain()
    {
        $registry = $this->newShadowedRegistry();

        $second = $registry->getNext('zim', '/first');
        $this->assertNotNull($second);
        $this->assertSame('/second', $second->path);
        $this->assertSame('zim', $second->name);

        // and again, from the one we just got -- chains deeper than two
        $third = $registry->getNext('zim', $second->path);
        $this->assertNotNull($third);
        $this->assertSame('/third', $third->path);
    }

    public function testGetNextReturnsTheTemplateItself()
    {
        $registry = $this->newShadowedRegistry();

        ob_start();
        $registry->getNext('zim', '/first')->template->__invoke();
        $this->assertSame(
            '/second' . DIRECTORY_SEPARATOR . 'zim.php',
            ob_get_clean()
        );
    }

    public function testGetNextIsNullAtTheEndOfTheChain()
    {
        $registry = $this->newShadowedRegistry();
        $this->assertNull($registry->getNext('zim', '/third'));
    }

    public function testGetNextSkipsPathsThatDoNotHaveTheTemplate()
    {
        $registry = new FakeTemplateRegistry;
        $registry->appendPath('/first');
        $registry->appendPath('/gap');
        $registry->appendPath('/third');
        $registry->fakefs['/first' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        $registry->fakefs['/third' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';

        $next = $registry->getNext('zim', '/first');
        $this->assertNotNull($next);
        $this->assertSame('/third', $next->path);
    }

    public function testGetNextIsNullWhenTheAfterPathIsNotInTheList()
    {
        $registry = $this->newShadowedRegistry();
        $this->assertNull($registry->getNext('zim', '/not-a-registered-path'));
    }

    public function testGetNextWalksNamespacedPaths()
    {
        $registry = new FakeTemplateRegistry;
        $registry->appendPath('/unrelated');
        $registry->appendPath('/ns-first', 'ns');
        $registry->appendPath('/ns-second', 'ns');
        $registry->fakefs['/unrelated' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        $registry->fakefs['/ns-first' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        $registry->fakefs['/ns-second' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';

        $next = $registry->getNext('ns::zim', '/ns-first');
        $this->assertNotNull($next);
        $this->assertSame('/ns-second', $next->path);

        // the un-namespaced paths are not part of a namespaced chain
        $this->assertNull($registry->getNext('ns::zim', '/ns-second'));
    }

    public function testGetNextIsNullForAnUnregisteredNamespace()
    {
        $registry = new FakeTemplateRegistry;
        $this->assertNull($registry->getNext('ns::zim', '/anywhere'));
    }

    public function testSetPathsStripsTrailingSeparators()
    {
        // prependPath()/appendPath() have always trimmed; setPaths() must
        // agree, or the same directory has two spellings inside the registry
        $registry = new FakeTemplateRegistry;
        $registry->setPaths(['/first' . DIRECTORY_SEPARATOR, '/second']);

        $this->assertSame(['/first', '/second'], $registry->getPaths());
    }

    public function testSetNamespacesStripsTrailingSeparators()
    {
        $registry = new FakeTemplateRegistry;
        $registry->setNamespaces([
            'ns' => ['/first' . DIRECTORY_SEPARATOR, '/second'],
        ]);

        $this->assertSame(['/first', '/second'], $registry->getNamespacePaths('ns'));
        $this->assertSame(['ns' => ['/first', '/second']], $registry->getNamespaces());
    }

    public function testConstructorStripsTrailingSeparators()
    {
        $registry = new FakeTemplateRegistry(
            [],
            ['/first' . DIRECTORY_SEPARATOR],
            ['ns' => ['/second' . DIRECTORY_SEPARATOR]]
        );

        $this->assertSame(['/first'], $registry->getPaths());
        $this->assertSame(['/second'], $registry->getNamespacePaths('ns'));
    }

    public function testGetNextWorksWhenPathsWereSetWithTrailingSeparators()
    {
        // the path recorded in $foundIn is fed straight back into getNext(),
        // so the two must be spelled identically
        $registry = new FakeTemplateRegistry;
        $registry->setPaths([
            '/first' . DIRECTORY_SEPARATOR,
            '/second' . DIRECTORY_SEPARATOR,
        ]);
        $registry->fakefs['/first' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        $registry->fakefs['/second' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';

        $this->assertSame('/first', $registry->getResolvedPath('zim'));

        $next = $registry->getNext('zim', $registry->getResolvedPath('zim'));
        $this->assertNotNull($next);
        $this->assertSame('/second', $next->path);
    }

    public function testGetNextWorksWhenNamespacesWereSetWithTrailingSeparators()
    {
        $registry = new FakeTemplateRegistry;
        $registry->setNamespaces([
            'ns' => [
                '/first' . DIRECTORY_SEPARATOR,
                '/second' . DIRECTORY_SEPARATOR,
            ],
        ]);
        $registry->fakefs['/first' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';
        $registry->fakefs['/second' . DIRECTORY_SEPARATOR . 'zim.php'] = 'fake';

        $next = $registry->getNext('ns::zim', $registry->getResolvedPath('ns::zim'));
        $this->assertNotNull($next);
        $this->assertSame('/second', $next->path);
    }

    public function testGetNamespaces()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->assertSame([], $this->template_registry->getNamespaces());

        $this->template_registry->appendPath('/no-namespace');
        $this->template_registry->appendPath('/bar', 'ns');
        $this->template_registry->appendPath('/baz', 'ns');
        $this->template_registry->prependPath('/foo', 'ns');
        $this->template_registry->appendPath('/dib', 'other');

        $expect = [
            'ns' => ['/foo', '/bar', '/baz'],
            'other' => ['/dib'],
        ];
        $this->assertSame($expect, $this->template_registry->getNamespaces());

        // the un-namespaced path stays out of the namespaces
        $this->assertSame(['/no-namespace'], $this->template_registry->getPaths());
    }

    public function testGetNamespacePaths()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $this->template_registry->appendPath('/bar', 'ns');
        $this->template_registry->appendPath('/baz', 'ns');

        $this->assertSame(
            ['/bar', '/baz'],
            $this->template_registry->getNamespacePaths('ns')
        );

        // an unregistered namespace has no paths
        $this->assertSame(
            [],
            $this->template_registry->getNamespacePaths('no-such-namespace')
        );
    }

    public function testGetNamespacesAfterSetNamespaces()
    {
        $this->template_registry = new FakeTemplateRegistry;
        $namespaces = ['ns' => ['/foo', '/bar']];
        $this->template_registry->setNamespaces($namespaces);
        $this->assertSame($namespaces, $this->template_registry->getNamespaces());
        $this->assertSame(['/foo', '/bar'], $this->template_registry->getNamespacePaths('ns'));
    }
}
