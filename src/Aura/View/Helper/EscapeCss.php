<?php
namespace Aura\View\Helper;

class EscapeCss extends Escape
{
    protected function escape($text)
    {
        // is $text composed only of allowed characters?
        $allowed = preg_match('/^[a-z0-9]*$/iDSu', $text);
        if ($allowed) {
            return $text;
        }

        // replace disallowed characters
        return preg_replace_callback(
            '/[^a-z0-9]/iDSu',
            [$this, 'replace'],
            $text
        );
    }

    protected function replace($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            $ord = ord($chr);
        } else {
            // convert UTF-8 to UTF-16BE
            $ord = hexdec(bin2hex($this->convert($chr)));
        }
        return sprintf('\\%X ', $ord);
    }
}
