<?php
declare(strict_types=1);

namespace Aura\View;

class FakeTemplateRegistry extends TemplateRegistry
{
    /**
     * A fake file system: file name => contents.
     *
     * @var array<string, string>
     */
    public array $fakefs = [];

    // read from the fake file system
    protected function isReadable(string $file): bool
    {
        // use parent for coverage
        parent::isReadable($file);
        // now use the fake
        return isset($this->fakefs[$file]);
    }

    // do not require the file; echo the resolved name instead, so tests can
    // assert on which file the path search picked.
    protected function enclose(string $__FILE__): \Closure
    {
        return function (array $__VARS__ = []) use ($__FILE__): void {
            echo $__FILE__;
        };
    }
}
