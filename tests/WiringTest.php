<?php
namespace Aura\View;

use Aura\Framework\Test\WiringAssertionsTrait;

class WiringTest extends \PHPUnit_Framework_TestCase
{
    use WiringAssertionsTrait;

    protected function setUp()
    {
        $this->loadDi();
    }

    public function testInstances()
    {
        $this->assertNewInstance('Aura\View\Helper\Datetime');
        $this->assertNewInstance('Aura\View\Helper\Escape');
        $this->assertNewInstance('Aura\View\Template');
        $this->assertNewInstance('Aura\View\TwoStep');
    }
    
    public function testHelpers()
    {
        $helper = $this->assertNewInstance('Aura\View\HelperLocator');
        $this->assertInstanceOf('Aura\View\Helper\Anchor',   $helper->get('anchor'));
        $this->assertInstanceOf('Aura\View\Helper\Attribs',  $helper->get('attribs'));
        $this->assertInstanceOf('Aura\View\Helper\Base',     $helper->get('base'));
        $this->assertInstanceOf('Aura\View\Helper\Datetime', $helper->get('datetime'));
        $this->assertInstanceOf('Aura\View\Helper\Escape',   $helper->get('escape'));
        $this->assertInstanceOf('Aura\View\Helper\Image',    $helper->get('image'));
        $this->assertInstanceOf('Aura\View\Helper\Input',    $helper->get('input'));
        $this->assertInstanceOf('Aura\View\Helper\Label',    $helper->get('label'));
        $this->assertInstanceOf('Aura\View\Helper\Links',    $helper->get('links'));
        $this->assertInstanceOf('Aura\View\Helper\Metas',    $helper->get('metas'));
        $this->assertInstanceOf('Aura\View\Helper\Radios',   $helper->get('radios'));
        $this->assertInstanceOf('Aura\View\Helper\Scripts',  $helper->get('scripts'));
        $this->assertInstanceOf('Aura\View\Helper\Scripts',  $helper->get('scriptsFoot'));
        $this->assertInstanceOf('Aura\View\Helper\Select',   $helper->get('select'));
        $this->assertInstanceOf('Aura\View\Helper\Styles',   $helper->get('styles'));
        $this->assertInstanceOf('Aura\View\Helper\Title',    $helper->get('title'));
        $this->assertInstanceOf('Aura\View\Helper\Textarea', $helper->get('textarea'));
    }
}
