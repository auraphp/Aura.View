<?php
namespace Aura\View\_Config;

use Aura\Di\_Config\AbstractContainerTest;

class CommonTest extends AbstractContainerTest
{
    protected function getConfigClasses()
    {
        return array(
            'Aura\View\_Config\Common',
        );
    }

    public function provideNewInstance()
    {
        return array(
            array('Aura\View\View'),
        );
    }
}
