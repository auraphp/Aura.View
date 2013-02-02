<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License\
 * 
 */
namespace Aura\View\Helper;

/**
 * 
 * Escapes output.
 * 
 * @package Aura.View
 * 
 */
class Escape
{
    /**
     * Entity Map mapping Unicode codepoints to any available named HTML entities.
     *
     * While HTML supports far more named entities, the lowest common denominator
     * has become HTML5's XML Serialisation which is restricted to the those named
     * entities that XML supports. Using HTML entities would result in this error:
     *     XML Parsing Error: undefined entity
     *
     * @var array
     */
    protected $entity_map = array(
        34 => 'quot',         // quotation mark
        38 => 'amp',          // ampersand
        60 => 'lt',           // less-than sign
        62 => 'gt',           // greater-than sign
    );

    /**
     * Static Matcher which escapes characters for HTML Attribute contexts
     *
     * @var callable
     */
    protected $match_attr;

    /**
     * Static Matcher which escapes characters for Javascript contexts
     *
     * @var callable
     */
    protected $match_js;

    /**
     * Static Matcher which escapes characters for CSS Attribute contexts
     *
     * @var callable
     */
    protected $match_css;

    /**
     * 
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->match_attr = array($this, 'match_attr');
        $this->match_js   = array($this, 'match_js');
        $this->match_css  = array($this, 'match_css');
    }

    /**
     * 
     * Escapes output.
     * 
     */
    public function __invoke()
    {
        $args = func_get_args();
        if (! $args) {
            return $this;
        }
        
        return $this->html($args[0]);
    }
    
    /**
     * 
     * Escape a string for the HTML Body context where there are very few characters
     * of special meaning. Internally this will use htmlspecialchars().
     *
     * @param mixed $spec
     * 
     * @return string
     * 
     */
    public function html($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $key = $this->html($key);
                $val = $this->html($val);
                $spec[$key] = $val;
            }
            return $spec;
        }
        
        return htmlspecialchars(
            $spec,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    /**
     * Escape a string for the HTML Attribute context. We use an extended set of characters
     * to escape that are not covered by htmlspecialchars() to cover cases where an attribute
     * might be unquoted or quoted illegally (e.g. backticks are valid quotes for IE).
     *
     * @param string $spec
     * @return string
     */
    public function attr($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $key = $this->attr($key);
                $val = $this->attr($val);
                $spec[$key] = $val;
            }
            return $spec;
        }
        
        $spec = $this->toUtf8($spec);
        if ($spec === '' || ctype_digit($spec)) {
            return $spec;
        }

        $result = preg_replace_callback('/[^a-z0-9,\.\-_]/iSu', $this->match_attr, $spec);
        return $this->fromUtf8($result);
    }

    /**
     * Escape a string for the Javascript context. This does not use json_encode(). An extended
     * set of characters are escaped beyond ECMAScript's rules for Javascript literal string
     * escaping in order to prevent misinterpretation of Javascript as HTML leading to the
     * injection of special characters and entities. The escaping used should be tolerant
     * of cases where HTML escaping was not applied on top of Javascript escaping correctly.
     * Backslash escaping is not used as it still leaves the escaped character as-is and so
     * is not useful in a HTML context.
     *
     * @param string $spec
     * @return string
     */
    public function js($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $key = $this->js($key);
                $val = $this->js($val);
                $spec[$key] = $val;
            }
            return $spec;
        }
        
        $spec = $this->toUtf8($spec);
        if ($spec === '' || ctype_digit($spec)) {
            return $spec;
        }

        $result = preg_replace_callback('/[^a-z0-9,\._]/iSu', $this->match_js, $spec);
        return $this->fromUtf8($result);
    }

    /**
     * Escape a string for the CSS context. CSS escaping can be applied to any string being
     * inserted into CSS and escapes everything except alphanumerics.
     *
     * @param string $spec
     * @return string
     */
    public function css($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $key = $this->css($key);
                $val = $this->css($val);
                $spec[$key] = $val;
            }
            return $spec;
        }
        
        $spec = $this->toUtf8($spec);
        if ($spec === '' || ctype_digit($spec)) {
            return $spec;
        }

        $result = preg_replace_callback('/[^a-z0-9]/iSu', $this->match_css, $spec);
        return $this->fromUtf8($result);
    }

    /**
     * Callback function for preg_replace_callback that applies HTML Attribute
     * escaping to all matches.
     *
     * @param array $matches
     * @return string
     */
    protected function match_attr($matches)
    {
        $chr = $matches[0];
        $ord = ord($chr);

        /**
         * The following replaces characters undefined in HTML with the
         * hex entity for the Unicode replacement character.
         */
        if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
            || ($ord >= 0x7f && $ord <= 0x9f)
        ) {
            return '&#xFFFD;';
        }

        /**
         * Check if the current character to escape has a name entity we should
         * replace it with while grabbing the integer value of the character.
         */
        if (strlen($chr) > 1) {
            $chr = $this->convertEncoding($chr, 'UTF-16BE', 'UTF-8');
        }

        $hex = bin2hex($chr);
        $ord = hexdec($hex);
        if (isset($this->entity_map[$ord])) {
            return '&' . $this->entity_map[$ord] . ';';
        }

        /**
         * Per OWASP recommendations, we'll use upper hex entities
         * for any other characters where a named entity does not exist.
         */
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        return sprintf('&#x%02X;', $ord);
    }

    /**
     * Callback function for preg_replace_callback that applies Javascript
     * escaping to all matches.
     *
     * @param array $matches
     * @return string
     */
    protected function match_js($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            return sprintf('\\x%02X', ord($chr));
        }
        $chr = $this->convertEncoding($chr, 'UTF-16BE', 'UTF-8');
        return sprintf('\\u%04s', strtoupper(bin2hex($chr)));
    }

    /**
     * Callback function for preg_replace_callback that applies CSS
     * escaping to all matches.
     *
     * @param array $matches
     * @return string
     */
    protected function match_css($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            $ord = ord($chr);
        } else {
            $chr = $this->convertEncoding($chr, 'UTF-16BE', 'UTF-8');
            $ord = hexdec(bin2hex($chr));
        }
        return sprintf('\\%X ', $ord);
    }

    /**
     * Converts a string to UTF-8 from the base encoding. The base encoding is set via this
     * class' constructor.
     *
     * @param string $spec
     * @throws Exception\RuntimeException
     * @return string
     */
    protected function toUtf8($spec)
    {
        if ($this->getEncoding() === 'utf-8') {
            $result = $spec;
        } else {
            $result = $this->convertEncoding($spec, 'UTF-8', $this->getEncoding());
        }

        if (!$this->isUtf8($result)) {
            throw new Exception\RuntimeException(sprintf(
                'String to be escaped was not valid UTF-8 or could not be converted: %s', $result
            ));
        }

        return $result;
    }

    /**
     * Converts a string from UTF-8 to the base encoding. The base encoding is set via this
     * class' constructor.
     * @param string $spec
     * @return string
     */
    protected function fromUtf8($spec)
    {
        if ($this->getEncoding() === 'utf-8') {
            return $spec;
        }

        return $this->convertEncoding($spec, $this->getEncoding(), 'UTF-8');
    }

    /**
     * Checks if a given string appears to be valid UTF-8 or not.
     *
     * @param string $spec
     * @return bool
     */
    protected function isUtf8($spec)
    {
        return ($spec === '' || preg_match('/^./su', $spec));
    }

    /**
     * Encoding conversion helper which wraps iconv and mbstring where they exist or throws
     * and exception where neither is available.
     *
     * @param string $spec
     * @param string $to
     * @param array|string $from
     * @throws Exception\RuntimeException
     * @return string
     */
    protected function convertEncoding($spec, $to, $from)
    {
        $result = '';
        if (function_exists('iconv')) {
            $result = iconv($from, $to, $spec);
        } elseif (function_exists('mb_convert_encoding')) {
            $result = mb_convert_encoding($spec, $to, $from);
        } else {
            throw new Exception\RuntimeException(
                get_called_class()
                . ' requires either the iconv or mbstring extension to be installed'
                . ' when escaping for non UTF-8 strings.'
            );
        }

        if ($result === false) {
            return ''; // return non-fatal blank string on encoding errors from users
        }
        return $result;
    }
}
