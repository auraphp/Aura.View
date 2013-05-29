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

use ArrayObject;

/**
 * 
 * A factory to create escaper objects.
 * 
 * @package Aura.View
 * 
 */
class EscaperFactory
{
    /**
     * 
     * The type of quoting to use for htmlspecialchars(), e.g. ENT_QUOTES.
     * 
     * @var int
     * 
     */
    protected $quotes = ENT_QUOTES;

    /**
     * 
     * The character set to use for htmlspecialchars(), e.g. 'UTF-8'.
     * 
     * @var string
     * 
     */
    protected $charset = 'UTF-8';

    /**
     * 
     * Returns a new instance of an escaper object.
     * 
     * @param mixed $spec The object to escape.
     * 
     * @return Escaper\Object
     * 
     */
    public function newInstance($spec)
    {
        if (is_array($spec)) {
            $flags = ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS;
            $spec = new ArrayObject($spec, $flags);
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
