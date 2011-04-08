<?php
namespace aura\view\helper;
class MockHelper extends AbstractHelper
{
    public function __invoke()
    {
        return "Hello Helper";
    }
}
