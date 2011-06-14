<?php
namespace Aura\View\Helper;
class MockHelper extends AbstractHelper
{
    public function __invoke()
    {
        return "Hello Helper";
    }
}
