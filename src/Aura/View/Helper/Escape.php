<?php
namespace Aura\View\Helper;

class Escape extends AbstractHelper
{
    public function __invoke($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $key = $this->escape($key);
                $val = $this->escape($val);
                $spec[$key] = $val;
            }
            return $spec;
        }
        
        return $this->escape($spec);
    }
    
    protected function escape($text)
    {
        return htmlspecialchars(
            $text,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
    
    protected function convert($chr)
    {
        $to = 'UTF-16BE';
        $from = 'UTF-8';
        
        if (function_exists('iconv')) {
            return (string) iconv($from, $to, $chr);
        }
        
        if (function_exists('mb_convert_encoding')) {
            return (string) mb_convert_encoding($chr, $to, $from);
        }
        
        throw new \Exception('need iconv or mbstring installed');
    }
}
