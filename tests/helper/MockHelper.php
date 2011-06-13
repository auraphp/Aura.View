<?php
namespace Aura\View\helper;
class MockHelper extends AbstractHelper
{
    public function __invoke()
    {
        return "Hello Helper";
    }
}
