<?php
namespace Aura\Html\Helper;
class MockHelper extends AbstractHelper
{
    public function __invoke($noun)
    {
        return "Hello $noun";
    }
}
