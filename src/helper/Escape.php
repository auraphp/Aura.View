<?php
namespace aura\view\helper;
class Escape extends AbstractHelper
{
    public function __invoke($text)
    {
        return $this->escape($text);
    }
}
