<?php
namespace aura\view;
class TwoStep
{
    protected $view_name;
    
    protected $view_data;
    
    protected $layout_name;
    
    protected $layout_data;
    
    protected $content_var;
    
    public function setViewName($view_name)
    {
        $this->view_name = $view_name;
    }
    
    public function setViewData($view_data)
    {
        $this->view_data = $view_data;
    }
    
    public function setViewFinder($view_finder)
    {
        $this->view_finder = $view_finder;
    }
    
    public function setLayoutName($layout_name)
    {
        $this->layout_name = $layout_name;
    }
    
    public function setLayoutData($layout_data)
    {
        $this->layout_data = $layout_data;
    }
    
    public function setLayoutFinder($layout_finder)
    {
        $this->layout_finder = $layout_finder;
    }
    
    public function setContentVar($content_var)
    {
        $this->content_var = $content_var;
    }
    
    public function render($view_finder, $layout_finder)
    {
        if ($this->view_name) {
            $tpl = $this->template_factory->newInstance();
            $tpl->setData($view_data);
            $tpl->setFinder($view_finder);
            $content = $view->fetch($this->view_name);
        } else {
            $content = null;
        }
        
        if ($this->layout_name) {
            // reuse the same template object, although
            // we lose any $this data from the earlier view
            $this->layout_data[$this->content_var] = $content;
            $tpl->setData($layout_data);
            $tpl->setFinder($layout_finder);
            return $tpl->fetch($layout_name);
        } else {
            return $content;
        }
    }
}
