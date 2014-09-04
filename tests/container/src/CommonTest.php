<?php
namespace Aura\View\_Config;

use Aura\Di\ContainerAssertionsTrait;

class CommonTest extends \PHPUnit_Framework_TestCase
{
    use ContainerAssertionsTrait;

    public function setUp()
    {
        $this->setUpContainer(array(
            'Aura\View\_Config\Common',
        ));
    }

    public function test()
    {
        $this->assertNewInstance('Aura\View\View');
    }
}
