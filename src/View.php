<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/MIT-license.php MIT
 *
 */
declare(strict_types=1);

namespace Aura\View;

/**
 *
 * A concrete TemplateView/TwoStepView pattern implementation.
 *
 * A _View_ is request-scoped: `__invoke()` mutates the instance while
 * rendering. Do not share one instance across requests in a long-lived worker;
 * build a new one per request.
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
     */
    public function __invoke(): string
    {
        $view = $this->getView();

        $this->setTemplateRegistry($this->getViewRegistry());
        $this->setContent($view === null ? '' : $this->render($view));

        $layout = $this->getLayout();
        if ($layout === null || $layout === '') {
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
     * @param array<string, mixed> $vars Variables to `extract()` within the
     * view as local variables. \Closure-based templates will need to call
     * `extract()` on their own.
     *
     */
    protected function render(string $name, array $vars = []): string
    {
        $template = $this->getTemplate($name);

        ob_start();
        try {
            $template->__invoke($vars);
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return (string) ob_get_clean();
    }
}
