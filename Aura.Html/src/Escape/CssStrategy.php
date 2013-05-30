<?php
namespace Aura\Html\Escape;

class CssStrategy extends AbstractStrategy
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
     * Callback to replace unsafe characters with escaped ones.
     * 
     * @param array $matches Characters matched from preg_replace_callback().
     * 
     * @return string Escaped characters.
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
