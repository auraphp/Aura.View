<?php
namespace Aura\View;

class TemplateRegistry
{
    protected $map = array();

    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    public function set($name, $spec)
    {
        if (is_string($spec)) {
            $__file__ = $spec;
            $spec = function () use ($__file__) {
                require $__file__;
            };
        }
        $this->map[$name] = $spec;
    }

    public function has($name)
    {
        return isset($this->map[$name]);
    }

    public function get($name)
    {
        if (! $this->has($name)) {
            throw new Exception\TemplateNotFound($name);
        }

        return $this->map[$name];
    }
}
