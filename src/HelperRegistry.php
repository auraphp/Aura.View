<?php
namespace Aura\View;

class HelperRegistry
{
    protected $map = array();

    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    public function __call($name, $args)
    {
        return call_user_func_array($this->get($name), $args);
    }

    public function set($name, $callable)
    {
        $this->map[$name] = $callable;
    }

    public function has($name)
    {
        return isset($this->map[$name]);
    }

    public function get($name)
    {
        if (! $this->has($name)) {
            throw new Exception\HelperNotFound($name);
        }

        return $this->map[$name];
    }
}
