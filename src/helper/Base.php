<?php
namespace aura\view\helper;
use aura\view\Helper;

/**
 * 
 * Helper for <base /> tags.
 * 
 */
class Base extends AbstractHelper
{
    /**
     * 
     * Returns a <base ... /> tag.
     * 
     * @param string|Solar_Uri $spec The base HREF.
     * 
     * @return string The <base ... /> tag.
     * 
     */
    public function __invoke($href)
    {
        $href = $this->escape($href);
        return $this->indent . "<base href=\"$href\" />" . PHP_EOL;
    }
}
