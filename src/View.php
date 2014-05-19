<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
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
     * Returns the rendered view along with any specified layout.
     * 
     * @return string
     * 
     */
    public function __invoke()
    {
        $this->setTemplateRegistry($this->getViewRegistry());
        $this->setContent($this->render($this->getView()));

        $layout = $this->getLayout();
        if (! $layout) {
            return $this->getContent();
        }

        $this->setTemplateRegistry($this->getLayoutRegistry());
        return $this->render($layout);
    }

    /**
     * 
     * Renders a template from the current template registry using output
     * buffering.
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
