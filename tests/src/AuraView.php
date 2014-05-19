<?php
namespace Aura\View;

class AuraView
{
    protected $engine;

    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function render($data, $view, $layout = '')
    {
        $this->engine->setData($data);
        $this->engine->setView($view);
        $this->engine->setLayout($layout);
        $output = $this->engine->__invoke();
        return $output;
    }
}
