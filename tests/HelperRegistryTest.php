<?php
declare(strict_types=1);

namespace Aura\View;

use PHPUnit\Framework\TestCase;

class HelperRegistryTest extends TestCase
{
    protected HelperRegistry $helper_registry;

    protected function setUp(): void
    {
        $this->helper_registry = new HelperRegistry;
    }

    public function testSetHasGet()
    {
        $foo = function () {
            return "Foo!";
        };

        $this->assertFalse($this->helper_registry->has('foo'));

        $this->helper_registry->set('foo', $foo);
        $this->assertTrue($this->helper_registry->has('foo'));

        $helper = $this->helper_registry->get('foo');
        $this->assertSame($foo, $helper);

        $this->expectException('Aura\View\Exception\HelperNotFound');
        $this->helper_registry->get('bar');
    }

    public function testCall()
    {
        $this->helper_registry->set('hello', function ($noun) {
            return "Hello {$noun}!";
        });

        $actual = $this->helper_registry->hello('World');
        $expect = 'Hello World!';
        $this->assertSame($expect, $actual);
    }

    public function testSetThrowsOnRedefinition()
    {
        $this->helper_registry->set('url', function () {
            return 'first';
        });

        $this->expectException('Aura\View\Exception\HelperAlreadyRegistered');
        $this->helper_registry->set('url', function () {
            return 'second';
        });
    }

    public function testRedefinitionExceptionNamesTheHelper()
    {
        $this->helper_registry->set('url', fn () => 'first');

        try {
            $this->helper_registry->set('url', fn () => 'second');
            $this->fail('Expected HelperAlreadyRegistered.');
        } catch (Exception\HelperAlreadyRegistered $e) {
            $this->assertStringContainsString('url', $e->getMessage());
        }
    }

    public function testSetThrowsEvenOnAnIdenticalCallable()
    {
        // registering the very same callable twice is still a collision: two
        // modules that happen to share a helper still need to say which wins.
        $same = fn () => 'same';
        $this->helper_registry->set('url', $same);

        $this->expectException('Aura\View\Exception\HelperAlreadyRegistered');
        $this->helper_registry->set('url', $same);
    }

    public function testSetLeavesTheFirstHelperInPlaceWhenItThrows()
    {
        $first = fn () => 'first';
        $this->helper_registry->set('url', $first);

        try {
            $this->helper_registry->set('url', fn () => 'second');
        } catch (Exception\HelperAlreadyRegistered $e) {
            // deliberately swallowed
        }

        $this->assertSame($first, $this->helper_registry->get('url'));
    }

    public function testSetWithOverrideReplaces()
    {
        $this->helper_registry->set('url', fn () => 'first');

        $second = fn () => 'second';
        $this->helper_registry->set('url', $second, override: true);

        $this->assertSame($second, $this->helper_registry->get('url'));
        $this->assertSame('second', $this->helper_registry->url());
    }

    public function testOverrideOnAnUnregisteredNameIsNotAnError()
    {
        // override means "I accept replacing whatever is there", not "there
        // must be something there".
        $helper = fn () => 'only';
        $this->helper_registry->set('url', $helper, override: true);

        $this->assertSame($helper, $this->helper_registry->get('url'));
    }

    public function testDistinctNamesDoNotCollide()
    {
        $this->helper_registry->set('url', fn () => 'url');
        $this->helper_registry->set('anchor', fn () => 'anchor');

        $this->assertSame('url', $this->helper_registry->url());
        $this->assertSame('anchor', $this->helper_registry->anchor());
    }

    public function testConstructorMapIsRegistered()
    {
        $registry = new HelperRegistry([
            'url' => fn () => 'url',
            'anchor' => fn () => 'anchor',
        ]);

        $this->assertTrue($registry->has('url'));
        $this->assertTrue($registry->has('anchor'));
        $this->assertSame('url', $registry->url());
    }

    public function testConstructorMapCollidesWithALaterSet()
    {
        $registry = new HelperRegistry(['url' => fn () => 'first']);

        $this->expectException('Aura\View\Exception\HelperAlreadyRegistered');
        $registry->set('url', fn () => 'second');
    }
}
