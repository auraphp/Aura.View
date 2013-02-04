<?php
namespace Aura\View\Helper;

class EscapeAttr extends Escape
{
    protected $entity_map = array(
        34 => '&quot;',
        38 => '&amp;',
        60 => '&lt;',
        62 => '&gt;',
    );

    protected function escape($text)
    {
        // is $text composed only of allowed characters?
        $allowed = preg_match('/^[a-z0-9\,\.\_\-]*$/iDSu', $text);
        if ($allowed) {
            return $text;
        }
        
        // replace disallowed characters
        return preg_replace_callback(
            '/[^a-z0-9\,\.\_\-]/iDSu',
            [$this, 'replace'],
            $text
        );
    }

    protected function replace($matches)
    {
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

        // handle mapped entities
        if (isset($this->entity_map[$ord])) {
            return $this->entity_map[$ord];
        }

        // handle upper hex entities for chars with no named entity
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        
        // everything else
        return sprintf('&#x%02X;', $ord);
    }
}
