<?php
namespace Aura\View\Helper;
class Escape extends AbstractHelper
{
    public function __invoke($text)
    {
        return $this->escape($text);
    }
}
