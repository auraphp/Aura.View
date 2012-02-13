<?php
namespace Aura\View;
class EscaperFactory
{
    protected $quotes;
    
    protected $charset;
    
    public function __construct($quotes, $charset)
    {
        
    }
    public function newInstance($source)
    {
        return new Escaper($source, $quotes, $charset);
    }
}
