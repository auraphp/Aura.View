<?php
namespace Aura\Html\Helper;

class EscapeHtml extends AbstractHelper
{
    public function __invoke($raw)
    {
        return $this->escaper->html($raw);
    }
}
