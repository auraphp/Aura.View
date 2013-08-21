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
        Template $template,
        $helper,
        Finder $view_finder,
        Finder $layout_finder
    ) {
        // retain the template and helper
        $this->template = $template;
        $this->template->setHelper($helper);
        
        // retain the finders
        $this->view_finder = $view_finder;
        $this->layout_finder = $layout_finder;
    }
    
    /**
     * 
     * Returns the layout finder.
     * 
     * @return Finder
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
     * Returns the Template object.
     * 
     * @return AbstractTemplate
     * 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * 
     * Sets the name of the variable in the layout template that should 
     * be replaced with the output of the view template.
     * 
     * @param string $content_var The variable name in the layout template.
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
     * Renders data into the view and layout, then returns the result.
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
        // set the template data
        $this->template->setData($data);
        
        // inject the view finder and retain the rendered result
        $this->template->setFinder($this->view_finder);
        $content = $this->template->render($view);
        
        // do we have a layout?
        if (! $layout) {
            // no, return just the view content
            return $content;
        }
        
        // set the content var in the template data
        $this->template->{$this->content_var} = $content;
        
        // inject the layout finder and return the rendered result
        $this->template->setFinder($this->layout_finder);
        return $this->template->render($layout);
    }
}
