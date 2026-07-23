<?php
declare(strict_types=1);

namespace Aura\View;

use PHPUnit\Framework\TestCase;

/**
 * Covers what a throwing template leaves behind. A render that fails partway
 * must not hand its output buffers or its half-open section frames to whatever
 * renders next.
 */
class RenderFailureTest extends TestCase
{
    protected View $view;

    protected function setUp(): void
    {
        $this->view = (new ViewFactory)->newInstance();

        $registry = $this->view->getViewRegistry();

        // throws while a section buffer is open, so two buffers are in flight
        $registry->set('throws_in_section', function () {
            $this->beginSection('leaked');
            echo 'half-written';
            throw new \RuntimeException('boom');
        });

        // throws with several section buffers stacked up
        $registry->set('throws_nested_sections', function () {
            $this->beginSection('outer');
            $this->beginSection('inner');
            throw new \RuntimeException('boom');
        });

        $registry->set('plain', function () {
            echo 'plain output';
        });

        // an endSection() with no beginSection() of its own; it must not be
        // able to consume a frame left behind by an earlier failure
        $registry->set('unmatched_end', function () {
            $this->endSection();
        });
    }

    protected function renderAndCatch(string $name): void
    {
        $this->view->setView($name);

        try {
            ($this->view)();
            $this->fail('Expected RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertSame('boom', $e->getMessage());
        }
    }

    public function testAFailedRenderClosesEveryBufferItOpened()
    {
        $before = ob_get_level();
        $this->renderAndCatch('throws_in_section');
        $this->assertSame($before, ob_get_level());
    }

    public function testAFailedRenderClosesEveryBufferWhenSectionsAreNested()
    {
        $before = ob_get_level();
        $this->renderAndCatch('throws_nested_sections');
        $this->assertSame($before, ob_get_level());
    }

    public function testOutputIsNotCorruptedByAnEarlierFailure()
    {
        $this->renderAndCatch('throws_in_section');

        // the next render must produce exactly its own output -- not the
        // orphaned buffer's contents, and not nothing at all because its
        // output went into a buffer nobody closes
        $this->view->setView('plain');
        $this->assertSame('plain output', ($this->view)());
    }

    public function testAFailedRenderLeavesNoSectionFrameBehind()
    {
        $this->renderAndCatch('throws_in_section');

        // with a stale frame still on the stack this silently captures under
        // 'leaked' instead of throwing, defeating the unmatched-endSection guard
        $this->view->setView('unmatched_end');
        $this->expectException('Aura\View\Exception');
        ($this->view)();
    }
}
