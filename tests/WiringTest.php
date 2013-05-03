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
        $this->assertInstanceOf('Aura\View\Helper\Anchor',        $helper->get('anchor'));
        $this->assertInstanceOf('Aura\View\Helper\Attribs',       $helper->get('attribs'));
        $this->assertInstanceOf('Aura\View\Helper\Base',          $helper->get('base'));
        $this->assertInstanceOf('Aura\View\Helper\Datetime',      $helper->get('datetime'));
        $this->assertInstanceOf('Aura\View\Helper\Escape',        $helper->get('escape'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Radios',   $helper->get('radios'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Select',   $helper->get('select'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Textarea', $helper->get('textarea'));
        $this->assertInstanceOf('Aura\View\Helper\Image',         $helper->get('image'));
        $this->assertInstanceOf('Aura\View\Helper\Links',         $helper->get('links'));
        $this->assertInstanceOf('Aura\View\Helper\Metas',         $helper->get('metas'));
        $this->assertInstanceOf('Aura\View\Helper\Ol',            $helper->get('ol'));
        $this->assertInstanceOf('Aura\View\Helper\Scripts',       $helper->get('scripts'));
        $this->assertInstanceOf('Aura\View\Helper\Scripts',       $helper->get('scriptsFoot'));
        $this->assertInstanceOf('Aura\View\Helper\Styles',        $helper->get('styles'));
        $this->assertInstanceOf('Aura\View\Helper\Tag',           $helper->get('tag'));
        $this->assertInstanceOf('Aura\View\Helper\Title',         $helper->get('title'));
        $this->assertInstanceOf('Aura\View\Helper\Ul',            $helper->get('ul'));
        
        $input = $this->assertNewInstance('Aura\View\Helper\Form\Input');
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $input->get('button'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $input->get('checkbox'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('color'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('date'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('datetime'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('datetime-local'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('email'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $input->get('file'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('hidden'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $input->get('image'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('month'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('number'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('password'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $input->get('radio'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('range'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $input->get('reset'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('search'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $input->get('submit'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('tel'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('text'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('time'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('url'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $input->get('week'));
        
        $field = $this->assertNewInstance('Aura\View\Helper\Form\Field');
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $field->get('button'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $field->get('checkbox'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('color'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('date'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('datetime'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('datetime-local'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('email'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $field->get('file'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('hidden'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $field->get('image'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('month'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('number'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('password'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $field->get('radio'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('range'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $field->get('reset'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('search'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $field->get('submit'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('tel'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('text'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('time'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('url'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $field->get('week'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Repeat',         $field->get('repeat'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Radios',         $field->get('radios'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Select',         $field->get('select'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Textarea',       $field->get('textarea'));
        
        $repeat = $this->assertNewInstance('Aura\View\Helper\Form\Repeat');
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $repeat->get('button'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $repeat->get('checkbox'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('color'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('date'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('datetime'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('datetime-local'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('email'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $repeat->get('file'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('hidden'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $repeat->get('image'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('month'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('number'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('password'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Checked',  $repeat->get('radio'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('range'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $repeat->get('reset'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('search'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Generic',  $repeat->get('submit'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('tel'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('text'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('time'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('url'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Input\Value',    $repeat->get('week'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Radios',         $repeat->get('radios'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Select',         $repeat->get('select'));
        $this->assertInstanceOf('Aura\View\Helper\Form\Textarea',       $repeat->get('textarea'));
    }
}
