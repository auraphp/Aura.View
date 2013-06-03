<?php
namespace Aura\View;

class MockTemplate extends Template
{
    public function __invoke()
    {
        echo 'Hello ' . $this->noun . '!';
    }
}
