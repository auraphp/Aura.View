<?php
namespace aura\view;
class TwoStep
{
    protected $view_name;
    
    protected $view_data = array();
    
    protected $view_paths = array();
    
    protected $layout_name;
    
    protected $layout_data = array();
    
    protected $layout_paths = array();
    
    protected $content_var = 'layout_content';
    
    public function __construct(Template $template)
    {
        $this->template = $template;
    }
    
    public function setViewName($view_name)
    {
        $this->view_name = $view_name;
    }
    
    public function setViewData($view_data)
    {
        $this->view_data = $view_data;
    }
    
    public function setViewPaths(array $view_paths = array())
    {
        $this->view_paths = $view_paths;
    }
    
    public function setLayoutName($layout_name)
    {
        $this->layout_name = $layout_name;
    }
    
    public function setLayoutData($layout_data)
    {
        $this->layout_data = $layout_data;
    }
    
    public function setLayoutPaths($layout_paths)
    {
        $this->layout_paths = $layout_paths;
    }
    
    public function setContentVar($content_var)
    {
        $this->content_var = $content_var;
    }
    
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
        // reuse the same template object; we lose $this data from the earlier
        // view, but we retain the plugin instances.
        if ($this->layout_name) {
            $this->layout_data[$this->content_var] = $content;
            $this->template->setData($this->layout_data);
            $this->template->setPaths($this->layout_paths);
            return $this->template->fetch($this->layout_name);
        } else {
            return $content;
        }
    }
}
