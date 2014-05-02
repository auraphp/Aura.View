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
     * Returns a rendered template by name.
     * 
     * @param string $__name__ The template to be rendered.
     * 
     * @param array $__data__ Variables to `extract()` into the local scope.
     * 
     * @return string
     * 
     */
    protected function render($__name__, array $__data__ = array())
    {
        unset($__data__['this']);
        unset($__data__['__name__']);
        extract($__data__);
        ob_start();
        $this->getTemplate($__name__)->__invoke();
        return ob_get_clean();
    }
}
