<?php
namespace aura\view;
class MockPlugin extends Plugin
{
    public function __invoke()
    {
        return "Hello Plugin";
    }
}
