<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

/**
 * 
 * A factory to create Escaper objects
 * 
 * @package Aura.View
 * 
 */
class EscaperFactory
{
    // FIXME
    /**
     *
     * flags in PHP, Default is ENT_QUOTES
     * 
     * @var int
     * 
     */
    protected $quotes = ENT_QUOTES;
    
    /**
     *
     * Character-set, Default to UTF-8
     * 
     * @var string 
     * 
     */
    protected $charset = 'UTF-8';
    
    /**
     * 
     * Constructor
     *
     * @param constant $quotes
     * 
     * @param string $charset 
     * 
     */
    public function __construct($quotes = ENT_QUOTES, $charset = 'UTF-8')
    {
        $this->quotes = $quotes;
        $this->charset = $charset;
    }
    
    // FIXME
    /**
     *
     * Create Escaper\Object
     * 
     * @param type $spec
     * 
     * @return Escaper\Object 
     * 
     */
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
