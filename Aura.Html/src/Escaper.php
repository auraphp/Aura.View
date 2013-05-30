<?php
namespace Aura\Html;

class Escaper
{
    /**
     * 
     * HTML entities mapped from ord() values.
     * 
     * @var array
     * 
     */
    protected $entities = [
        34 => '&quot;',
        38 => '&amp;',
        60 => '&lt;',
        62 => '&gt;',
    ];

    /**
     * 
     * Encoding for raw and escaped values.
     *
     * @var string
     * 
     */
    protected $encoding = 'utf-8';

    /**
     * 
     * All supported encodings.
     *
     * @var array
     * 
     */
    protected $supported_encodings = [
        '1251', '1252', '866', '932', '936', '950', 'big5', 'big5-hkscs',
        'cp1251', 'cp1252', 'cp866', 'cp932', 'euc-jp', 'eucjp', 'eucjp-win',
        'gb2312', 'ibm866', 'iso-8859-1', 'iso-8859-15', 'iso-8859-5',
        'iso8859-1', 'iso8859-15', 'iso8859-5', 'koi8-r', 'koi8-ru', 'koi8r',
        'macroman', 'shift_jis', 'sjis', 'sjis-win', 'utf-8', 'win-1251',
        'windows-1251', 'windows-1252',
    ];

    /**
     * 
     * Constructor.
     *
     * @param string $encoding The encoding for raw and escaped strings.
     * 
     */
    public function __construct($encoding = null)
    {
        $encoding = trim($encoding);
        if ($encoding) {
            $this->setEncoding($encoding);
        }
    }

    /**
     * 
     * Sets the encoding for raw and escaped strings.
     * 
     * @param string $encoding The encoding.
     * 
     * @return void
     * 
     */
    public function setEncoding($encoding)
    {
        $encoding = strtolower($encoding);
        if (! in_array($encoding, $this->supported_encodings)) {
            throw new Exception\EncodingNotSupported($encoding);
        }
        $this->encoding = $encoding;
    }
    
    /**
     * 
     * Returns the encoding for raw and escaped strings.
     *
     * @return string
     * 
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * 
     * Escapes for unquoted HTML attribute context.
     *
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    public function attr($raw)
    {
        // pre-empt escaping
        if ($raw === '' || ctype_digit($raw)) {
            return $raw;
        }

        // escape the string in UTF-8 encoding
        $esc = preg_replace_callback(
            '/[^a-z0-9,\.\-_]/iSu',
            [$this, 'replaceAttr'],
            $this->toUtf8($raw)
        );
        
        // return using original encoding
        return $this->fromUtf8($esc);
    }

    /**
     * 
     * Escapes for HTML body and quoted HTML attribute context.
     *
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    public function html($raw)
    {
        // pre-empt escaping
        if ($raw === '' || ctype_digit($raw)) {
            return $raw;
        }

        // return the escaped string
        return htmlspecialchars(
            $raw, 
            ENT_QUOTES | ENT_SUBSTITUTE,
            $this->encoding,
            false // do not double-escape
        );
    }

    /**
     * 
     * Escapes for CSS context.
     *
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    public function css($raw)
    {
        // pre-empt escaping
        if ($raw === '' || ctype_digit($raw)) {
            return $raw;
        }

        // escape the string in UTF-8 encoding
        $esc = preg_replace_callback(
            '/[^a-z0-9]/iSu',
            [$this, 'replaceCss'],
            $this->toUtf8($raw)
        );
        
        // return using original encoding
        return $this->fromUtf8($esc);
    }

    /**
     * 
     * Escapes for JavaScript context.
     *
     * @param string $raw The raw string.
     * 
     * @return string The escaped string.
     * 
     */
    public function js($raw)
    {
        // pre-empt escaping
        if ($raw === '' || ctype_digit($raw)) {
            return $raw;
        }

        // escape the string in UTF-8 encoding
        $esc = preg_replace_callback(
            '/[^a-z0-9,\._]/iSu',
            [$this, 'replaceJs'],
            $this->toUtf8($raw)
        );
        
        // return using original encoding
        return $this->fromUtf8($esc);
    }

    /**
     * 
     * Replaces unsafe HTML attribute characters.
     *
     * @param array $matches A match from preg_replace_callback().
     * 
     * @return string Escaped characters.
     * 
     */
    protected function replaceAttr($matches)
    {
        // get the character and its ord() value
        $chr = $matches[0];
        $ord = ord($chr);

        // handle characters undefined in HTML
        $undef = ($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
              || ($ord >= 0x7f && $ord <= 0x9f);
        if ($undef) {
            // use the Unicode replacement char
            return '&#xFFFD;';
        }

        // convert UTF-8 to UTF-16BE
        if (strlen($chr) > 1) {
            $chr = $this->convert($chr, 'UTF-16BE', 'UTF-8');
        }

        // retain the ord value
        $ord = hexdec(bin2hex($chr));
        
        // is this a mapped entity?
        if (isset($this->entities[$ord])) {
            return $this->entities[$ord];
        }

        // is this an upper-range hex entity?
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        
        // everything else
        return sprintf('&#x%02X;', $ord);
    }

    /**
     * 
     * Replaces unsafe JavaScript attribute characters.
     *
     * @param array $matches A match from preg_replace_callback().
     * 
     * @return string Escaped characters.
     * 
     */
    protected function replaceCss($matches)
    {
        // get the character
        $chr = $matches[0];
        
        // is it UTF-8?
        if (strlen($chr) == 1) {
            // yes
            $ord = ord($chr);
        } else {
            // no
            $chr = $this->convert($chr, 'UTF-16BE', 'UTF-8');
            $ord = hexdec(bin2hex($chr));
        }
        
        // done
        return sprintf('\\%X ', $ord);
    }

    /**
     * 
     * Replaces unsafe JavaScript attribute characters.
     *
     * @param array $matches A match from preg_replace_callback().
     * 
     * @return string Escaped characters.
     * 
     */
    protected function replaceJs($matches)
    {
        // get the character
        $chr = $matches[0];
        
        // is it UTF-8?
        if (strlen($chr) == 1) {
            // yes
            return sprintf('\\x%02X', ord($chr));
        } else {
            // no
            $chr = $this->convert($chr, 'UTF-16BE', 'UTF-8');
            return sprintf('\\u%04s', strtoupper(bin2hex($chr)));
        }
    }

    /**
     * 
     * Converts a string to UTF-8 encoding.
     *
     * @param string $str The string to be converted.
     * 
     * @return string The UTF-8 string.
     * 
     */
    protected function toUtf8($str)
    {
        // pre-empt converting
        if ($str === '') {
            return $str;
        }
        
        // do we need to convert it?
        if ($this->encoding != 'utf-8') {
            // convert to UTF-8
            $str = $this->convert($str, 'UTF-8', $this->encoding);
        }

        // do we have a valid UTF-8 string?
        if (! preg_match('/^./su', $str)) {
            throw new Exception\InvalidUtf8($str);
        }

        // looks ok, return the encoded version
        return $str;
    }

    /**
     * 
     * Converts a string from UTF-8.
     * 
     * @param string $str The UTF-8 string.
     * 
     * @return string The string in its original encoding.
     * 
     */
    protected function fromUtf8($str)
    {
        if ($this->encoding == 'utf-8') {
            return $str;
        }
        
        return $this->convert($str, $this->encoding, 'UTF-8');
    }

    /**
     * 
     * Converts a string to a new encoding from an old one.
     *
     * @param string $str The string to be converted.
     * 
     * @param string $new Convert to this encoding.
     * 
     * @param string $old The existing encoding for the string.
     * 
     * @return string The string in the new encoding.
     * 
     */
    protected function convert($str, $new, $old)
    {
        if (function_exists('iconv')) {
            return (string) iconv($old, $new, $str);
        }
        
        if (function_exists('mb_convert_encoding')) {
            return (string) mb_convert_encoding($str, $new, $old);
        }
        
        throw new Exception\ExtensionNotInstalled("Extension 'iconv' or 'mbstring' is required.");
    }
}
