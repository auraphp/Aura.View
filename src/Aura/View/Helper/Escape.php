<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View\Helper;

/**
 * 
 * Escape special characters
 * 
 * @package Aura.View
 * 
 */
class Escape extends AbstractHelper
{
    public function __invoke($text)
    {
        return $this->escape($text);
    }
}
