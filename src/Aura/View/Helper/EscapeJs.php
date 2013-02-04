<?php
namespace Aura\View\Helper;

class EscapeJs extends Escape
{
    protected function escape($text)
    {
        // is $text composed only of allowed characters?
        $allowed = preg_match('/^[a-z0-9,._]*$/iDSu', $text);
        if ($allowed) {
            return $text;
        }

        // replace disallowed characters
        return preg_replace_callback(
            '/[^a-z0-9,._]/iDSu',
            [$this, 'replace'],
            $text
        );
    }

    protected function replace($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            return sprintf('\\x%02X', ord($chr));
        }
        
        // convert UTF-8 to UTF-16BE
        return sprintf('\\u%04s', strtoupper(bin2hex($this->convert($chr))));
    }
}
