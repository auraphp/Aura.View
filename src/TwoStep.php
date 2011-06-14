<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

/**
 * 
 * Provides a Two Step View pattern implementation; this allows for an 
 * inner "view" and and outer "layout" as commonly used by web apps.
 * 
 * @package Aura.View
 * 
 */
class TwoStep
{
    /**
     * 
     * The name of the inner view template.
     * 
     * @var string
     * 
     */
    protected $view_name;
    
    /**
     * 
     * The data for the inner view template.
     * 
     * @var array
     * 
     */
    protected $view_data = array();
    
    /**
     * 
     * The paths to search when finding the inner view template.
     * 
     * @var array
     * 
     */
    protected $view_paths = array();
    
    /**
     * 
     * The name of the outer layout template.
     * 
     * @var string
     * 
     */
    protected $layout_name;
    
    /**
     * 
     * The data for the outer layout template.
     * 
     * @var array
     * 
     */
    protected $layout_data = array();
    
    /**
     * 
     * The paths to search when finding the outer layout template.
     * 
     * @var array
     * 
     */
    protected $layout_paths = array();
    
    /**
     * 
     * The name of the variable in the outer layout template that should be
     * replaced with the output of the inner view template.
     * 
     * @var string
     * 
     */
    protected $layout_content_var = 'layout_content';
    
    /**
     * 
     * The Template object to be used when rendering the inner view and outer
     * layout.
     * 
     * @var Template
     * 
     */
    protected $template;
    
    /**
     * 
     * Constructor.
     * 
     * @param Template $template The Template object to be used when rendering
     * the inner view and outer layout.
     * 
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }
    
    /**
     * 
     * Sets the name of the inner view template.
     * 
     * @param string $view_name The name of the inner view template.
     * 
     * @return void
     * 
     */
    public function setViewName($view_name)
    {
        $this->view_name = $view_name;
    }
    
    /**
     * 
     * Sets the data for the inner view template.
     * 
     * @param array $view_data The data for the inner view template.
     * 
     * @return void
     * 
     */
    public function setViewData($view_data)
    {
        $this->view_data = $view_data;
    }
    
    /**
     * 
     * Sets the paths to search when finding the inner view template.
     * 
     * @param string $view_paths The paths to search when finding the inner 
     * view template.
     * 
     * @return void
     * 
     */
    public function setViewPaths(array $view_paths = array())
    {
        $this->view_paths = $view_paths;
    }
    
    public function addViewPath($path)
    {
        $this->view_paths[] = $path;
    }
    
    /**
     * 
     * Sets the name of the outer layout template.
     * 
     * @param string $layout_name The name of the outer layout template.
     * 
     * @return void
     * 
     */
    public function setLayoutName($layout_name)
    {
        $this->layout_name = $layout_name;
    }
    
    /**
     * 
     * Sets the data for the outer layout template.
     * 
     * @param array $layout_data The data for the outer layout template.
     * 
     * @return void
     * 
     */
    public function setLayoutData($layout_data)
    {
        $this->layout_data = $layout_data;
    }
    
    /**
     * 
     * Sets the paths to search when finding the outer layout template.
     * 
     * @param string $layout_paths The paths to search when finding the outer 
     * layout template.
     * 
     * @return void
     * 
     */
    public function setLayoutPaths(array $layout_paths = array())
    {
        $this->layout_paths = $layout_paths;
    }
    
    public function addLayoutPath($path)
    {
        $this->layout_paths[] = $path;
    }
    
    /**
     * 
     * Sets the name of the variable in the outer layout template that should 
     * be replaced with the output of the inner view template.
     * 
     * @param string $layout_content_var
     * 
     * @return void
     * 
     */
    public function setLayoutContentVar($layout_content_var)
    {
        $this->layout_content_var = $layout_content_var;
    }
    
    /**
     * 
     * Renders the inner view and outer layout templates and returns the
     * resulting output.
     * 
     * @return string The rendered two-step view results.
     * 
     */
    public function render()
    {
        // no view? no layout? nothing to render.
        if (! $this->view_name && ! $this->layout_name) {
            return;
        }
        
        // render the view and get its content for the layout
        if ($this->view_name) {
            $this->template->setData($this->view_data);
            $this->template->setPaths($this->view_paths);
            $content = $this->template->fetch($this->view_name);
        } else {
            $content = null;
        }
        
        // render the layout and inject the view content.
        // note that we *add* the layout data, which merges it with the
        // previous view data instead of removing it.
        if ($this->layout_name) {
            $this->layout_data[$this->layout_content_var] = $content;
            $this->template->addData($this->layout_data);
            $this->template->setPaths($this->layout_paths);
            return $this->template->fetch($this->layout_name);
        } else {
            return $content;
        }
    }
}
