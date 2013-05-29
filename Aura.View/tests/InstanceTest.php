<?php
namespace Aura\View;

class InstanceTest extends \PHPUnit_Framework_TestCase
{
    protected $instance;
    
    protected function setUp()
    {
        $this->instance = require dirname(__DIR__) . '/scripts/instance.php';
    }
    
    public function testInstance()
    {
        $this->assertInstanceOf('Aura\View\Template', $this->instance);
    }
    
    public function testHelpers()
    {
        $helper = $this->instance->getHelperLocator();
        
        $name_class = [
            'anchor'        => 'Aura\View\Helper\Anchor',
            'attribs'       => 'Aura\View\Helper\Attribs',
            'base'          => 'Aura\View\Helper\Base',
            'datetime'      => 'Aura\View\Helper\Datetime',
            'escape'        => 'Aura\View\Helper\Escape',
            'field'         => 'Aura\View\Helper\Form\Field',
            'image'         => 'Aura\View\Helper\Image',
            'input'         => 'Aura\View\Helper\Form\Input',
            'links'         => 'Aura\View\Helper\Links',
            'metas'         => 'Aura\View\Helper\Metas',
            'ol'            => 'Aura\View\Helper\Ol',
            'radios'        => 'Aura\View\Helper\Form\Radios',
            'repeat'        => 'Aura\View\Helper\Form\Repeat',
            'scripts'       => 'Aura\View\Helper\Scripts',
            'scriptsFoot'   => 'Aura\View\Helper\Scripts',
            'select'        => 'Aura\View\Helper\Form\Select',
            'styles'        => 'Aura\View\Helper\Styles',
            'tag'           => 'Aura\View\Helper\Tag',
            'title'         => 'Aura\View\Helper\Title',
            'textarea'      => 'Aura\View\Helper\Form\Textarea',
            'ul'            => 'Aura\View\Helper\Ul',
        ];
        
        foreach ($name_class as $name => $class) {
            $this->assertInstanceOf($class, $helper->get($name));
        }
    }
    
    public function testInputHelper()
    {
        $input = $this->instance->getHelperLocator()->get('input');
        
        $name_class = [
            'button'         => 'Aura\View\Helper\Form\Input\Generic',
            'checkbox'       => 'Aura\View\Helper\Form\Input\Checked',
            'color'          => 'Aura\View\Helper\Form\Input\Value',
            'date'           => 'Aura\View\Helper\Form\Input\Value',
            'datetime'       => 'Aura\View\Helper\Form\Input\Value',
            'datetime-local' => 'Aura\View\Helper\Form\Input\Value',
            'email'          => 'Aura\View\Helper\Form\Input\Value',
            'file'           => 'Aura\View\Helper\Form\Input\Generic',
            'hidden'         => 'Aura\View\Helper\Form\Input\Value',
            'image'          => 'Aura\View\Helper\Form\Input\Generic',
            'month'          => 'Aura\View\Helper\Form\Input\Value',
            'number'         => 'Aura\View\Helper\Form\Input\Value',
            'password'       => 'Aura\View\Helper\Form\Input\Value',
            'radio'          => 'Aura\View\Helper\Form\Input\Checked',
            'range'          => 'Aura\View\Helper\Form\Input\Value',
            'reset'          => 'Aura\View\Helper\Form\Input\Generic',
            'search'         => 'Aura\View\Helper\Form\Input\Value',
            'submit'         => 'Aura\View\Helper\Form\Input\Generic',
            'tel'            => 'Aura\View\Helper\Form\Input\Value',
            'text'           => 'Aura\View\Helper\Form\Input\Value',
            'time'           => 'Aura\View\Helper\Form\Input\Value',
            'url'            => 'Aura\View\Helper\Form\Input\Value',
            'week'           => 'Aura\View\Helper\Form\Input\Value',
        ];
        
        foreach ($name_class as $name => $class) {
            $this->assertInstanceOf($class, $input->get($name));
        }
    }
    
    public function testFieldHelper()
    {
        $field = $this->instance->getHelperLocator()->get('field');
        
        $name_class = [
            'button'         => 'Aura\View\Helper\Form\Input\Generic',
            'checkbox'       => 'Aura\View\Helper\Form\Input\Checked',
            'color'          => 'Aura\View\Helper\Form\Input\Value',
            'date'           => 'Aura\View\Helper\Form\Input\Value',
            'datetime'       => 'Aura\View\Helper\Form\Input\Value',
            'datetime-local' => 'Aura\View\Helper\Form\Input\Value',
            'email'          => 'Aura\View\Helper\Form\Input\Value',
            'file'           => 'Aura\View\Helper\Form\Input\Generic',
            'hidden'         => 'Aura\View\Helper\Form\Input\Value',
            'image'          => 'Aura\View\Helper\Form\Input\Generic',
            'month'          => 'Aura\View\Helper\Form\Input\Value',
            'number'         => 'Aura\View\Helper\Form\Input\Value',
            'password'       => 'Aura\View\Helper\Form\Input\Value',
            'radio'          => 'Aura\View\Helper\Form\Input\Checked',
            'range'          => 'Aura\View\Helper\Form\Input\Value',
            'reset'          => 'Aura\View\Helper\Form\Input\Generic',
            'search'         => 'Aura\View\Helper\Form\Input\Value',
            'submit'         => 'Aura\View\Helper\Form\Input\Generic',
            'tel'            => 'Aura\View\Helper\Form\Input\Value',
            'text'           => 'Aura\View\Helper\Form\Input\Value',
            'time'           => 'Aura\View\Helper\Form\Input\Value',
            'url'            => 'Aura\View\Helper\Form\Input\Value',
            'week'           => 'Aura\View\Helper\Form\Input\Value',
            'radios'         => 'Aura\View\Helper\Form\Radios',
            'select'         => 'Aura\View\Helper\Form\Select',
            'textarea'       => 'Aura\View\Helper\Form\Textarea',
        ];
        
        foreach ($name_class as $name => $class) {
            $this->assertInstanceOf($class, $field->get($name));
        }
    }
}
