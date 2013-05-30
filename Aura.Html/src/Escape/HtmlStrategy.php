<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.Html
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Html\Escape;

/**
 * 
 * Strategy to escape values for HTML content and quoted attributes.
 * 
 * Serves as a base class for other escapers.
 * 
 * @package Aura.Html
 * 
 */
class HtmlStrategy extends AbstractStrategy
{
    /**
     * 
     * Callback to replace unsafe characters with escaped ones.
     * 
     * @param array $matches Characters matched from preg_replace_callback().
     * 
     * @return string Escaped characters.
     * 
     */
    protected function replace(array $matches)
    {
        return htmlspecialchars(
            $matches[0],
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8',
            false // do not double-encode
        );
    }
}
