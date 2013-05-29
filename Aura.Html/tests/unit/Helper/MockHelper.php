<?php
namespace Aura\Html\Helper;
class MockHelper extends AbstractHelper
{
    public function __invoke()
    {
        return "Hello Helper";
    }
}
