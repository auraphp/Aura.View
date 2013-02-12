<?php
namespace Aura\View\Helper;

class EscapeCss extends Escape
{
    /**
     * 
     * A regular expression character class considered "safe" by the escaper.
     * 
     * @var string
     * 
     */
    protected $safe = 'a-z0-9';
    
    /**
     * 
     * Callback method to replace an unsafe character with an escaped one.
     * 
     * @param array $matches Matches from preg_replace_callback().
     * 
     * @return string An escaped character.
     * 
     */
    protected function replace(array $matches)
    {
        // get the character
        $chr = $matches[0];
        
        // is it UTF-8?
        if (strlen($chr) == 1) {
            // yes
            $ord = ord($chr);
        } else {
            // no
            $chr = $this->convert($chr);
            $ord = hexdec(bin2hex($chr));
        }
        
        // done
        return sprintf('\\%X ', $ord);
    }
}
