<?php
namespace Aura\View;
class EscaperFactory
{
    protected $quotes = ENT_QUOTES;
    
    protected $charset = 'UTF-8';
    
    public function __construct($quotes = ENT_QUOTES, $charset = 'UTF-8')
    {
        $this->quotes = $quotes;
        $this->charset = $charset;
    }
    
    public function newInstance($spec)
    {
        if (is_array($spec)) {
            $spec = new \ArrayObject($spec);
        }
        
        if ($spec instanceof \IteratorAggregate) {
            return new Escaper\IteratorAggregate($this, $spec, $this->quotes, $this->charset);
        }
        
        if ($spec instanceof \Iterator) {
            return new Escaper\Iterator($this, $spec, $this->quotes, $this->charset);
        }
        
        return new Escaper\Object($this, $spec, $this->quotes, $this->charset);
    }
}
