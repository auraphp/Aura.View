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
namespace Aura\View\Helper;

use Aura\View\EscaperFactory;

/**
 * 
 * Inline escaper.
 * 
 * @package Aura.View
 * 
 */
class Escape
{
    /**
     * 
     * An escaper object so we have access to its __escape() method.
     * 
     * @var Aura\View\Escaper\Object
     * 
     */
    protected $escaper_object;

    /**
     * 
     * Constructor.
     * 
     * @param EscaperFactory $escaper_factory A factory to create the 
     * escaper object.
     * 
     */
    public function __construct(EscaperFactory $escaper_factory)
    {
        $this->escaper_object = $escaper_factory->newInstance((object) []);
    }

    /**
     * 
     * Escapes strings; wraps objects and arrays in escaper objects; leaves
     * booleans/numbers/resources/nulls alone.
     * 
     * @param mixed $val The value to escape.
     * 
     */
    public function __invoke($val)
    {
        return $this->escaper_object->__escape($val);
    }
}
