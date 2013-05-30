<?php
namespace Aura\Html\Escape;

class AttrStrategy extends AbstractStrategy
{
    /**
     * 
     * A regular expression character class considered "safe" by the escaper.
     * 
     * @var string
     * 
     */
    protected $safe = 'a-z0-9,._-';
    
    /**
     * 
     * HTML entities mapped from ord() values.
     * 
     * @var array
     * 
     */
    protected $entity = array(
        34 => '&quot;',
        38 => '&amp;',
        60 => '&lt;',
        62 => '&gt;',
    );

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
        // get the character and its ord() value
        $chr = $matches[0];
        $ord = ord($chr);

        // handle chars undefined in HTML
        $undefined = ($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
                  || ($ord >= 0x7f && $ord <= 0x9f);
        
        if ($undefined) {
            // use the Unicode replacement char
            return '&#xFFFD;';
        }

        // convert UTF-8 to UTF-16BE
        if (strlen($chr) > 1) {
            $chr = $this->convert($chr);
        }
        
        // retain the ord value
        $ord = hexdec(bin2hex($chr));

        // is this a mapped entity?
        if (isset($this->entity[$ord])) {
            return $this->entity[$ord];
        }

        // is this an upper-range hex entity?
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        
        // everything else
        return sprintf('&#x%02X;', $ord);
    }
}
