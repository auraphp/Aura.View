<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

/**
 * 
 * Provides a TwoStepView pattern implementation; this allows for view and
 * layout as commonly used by web apps.
 * 
 * @package Aura.View
 * 
 */
class Manager
{
    /**
     * 
     * The name of the variable in the layout template that should be
     * replaced with the output of the view template.
     * 
     * @var string
     * 
     */
    protected $content_var = 'content';

    /**
     * 
     * The shared data for view and layout.
     * 
     * @var array
     * 
     */
    protected $data = [];

    /**
     * 
     * The factory for template objects.
     * 
     * @var Factory
     * 
     */
    protected $factory;

    /**
     * 
     * The finder for layouts.
     * 
     * @var mixed
     * 
     */
    protected $layout_finder;

    /**
     * 
     * The finder for views.
     * 
     * @var mixed
     * 
     */
    protected $view_finder;

    /**
     * 
     * Constructor.
     * 
     */
    public function __construct(
        Factory $factory,
        $helper,
        Finder $view_finder,
        Finder $layout_finder
    ) {
        $this->factory       = $factory;
        $this->helper        = $helper;
        $this->view_finder   = $view_finder;
        $this->layout_finder = $layout_finder;
    }
    
    /**
     * 
     * Returns the template factory.
     * 
     * @return Factory
     * 
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * 
     * Returns the helper object.
     * 
     * @return object
     * 
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * 
     * Returns the layout finder.
     * 
     * @return Factory
     * 
     */
    public function getLayoutFinder()
    {
        return $this->layout_finder;
    }

    /**
     * 
     * Returns the view finder.
     * 
     * @return Finder
     * 
     */
    public function getViewFinder()
    {
        return $this->view_finder;
    }

    /**
     * 
     * Sets the name of the variable in the layout template that should 
     * be replaced with the output of the view template.
     * 
     * @param string $content_var The variable name in the layout
     * template.
     * 
     * @return void
     * 
     */
    public function setContentVar($content_var)
    {
        $this->content_var = $content_var;
    }

    /**
     * 
     * Returns the name of the variable in the layout template that should 
     * be replaced with the content of the view template.
     * 
     * @return string
     * 
     */
    public function getContentVar()
    {
        return $this->content_var;
    }

    /**
     * 
     * Renders the view and layout and returns the resulting output,
     * negotiating a format from the accept header values.
     * 
     * @param object $data The data for rendering.
     * 
     * @param string $view The name of the view to be rendered.
     * 
     * @param string $layout The name of the layout to be rendered (if any).
     * 
     * @return string The rendered output.
     * 
     */
    public function render($data, $view, $layout = null)
    {
        // force the data to an object so it can be shared
        $data = (object) $data;
        
        // render the view and retain the output
        $content = $this->renderStep($this->view_finder, $view, $data);
        
        // do we have a layout?
        if (! $layout) {
            // no, return just the view
            return $content;
        }
        
        // assign the content to the data
        $data->{$this->content_var} = $content;
        
        // render and return the layout
        return $this->renderStep($this->layout_finder, $layout, $data);
    }

    /**
     * 
     * Renders a template.
     * 
     * @var string $spec The template name.
     * 
     * @return string
     * 
     */
    protected function renderStep(Finder $finder, $spec, $data)
    {
        // inject the correct finder into the factory
        $this->factory->setFinder($finder);
        
        // find the template
        $template = $this->factory->newInstance($spec, $this->helper, $data);
        if (! $template) {
            throw new Exception\TemplateNotFound($spec);
        }
        
        // render and return the template
        ob_start();
        $template();
        return ob_get_clean();
    }
}
