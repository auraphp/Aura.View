<?php
namespace aura\view\helper;
class Attribs extends AbstractHelper
{
    public function __invoke(array $attribs)
    {
        return $this->attribs($attribs);
    }
}
