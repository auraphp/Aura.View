<?php
namespace Aura\View;

/**
 * 
 * A concrete TemplateView/TwoStepView pattern implementation.
 * 
 * @package Aura.View
 * 
 */
class View extends AbstractView
{
    /**
     * 
     * Returns the rendered view, optionally with layout.
     * 
     * @return string
     * 
     */
    public function __invoke()
    {
        $this->setTemplateRegistry($this->getViewRegistry());
        $content = $this->render($this->getView());

        $layout = $this->getLayout();
        if (! $layout) {
            return $content;
        }

        $content_var = $this->getContentVar();
        $this->getData()->$content_var = $content;
        $this->setTemplateRegistry($this->getLayoutRegistry());
        return $this->render($layout);
    }

    /**
     * 
     * Renders a template by name.
     * 
     * @param string $name The name of the template to be rendered.
     * 
     * @return string
     * 
     */
    protected function render($name)
    {
        ob_start();
        $this->getTemplate($name)->__invoke();
        return ob_get_clean();
    }
}
