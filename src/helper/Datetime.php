<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View\helper;

/**
 * 
 * Helper for a formatted timestamp using date() format codes.
 * 
 * Default format is "Y-m-d H:i:s".
 * 
 * @package aura.view
 * 
 */
class Datetime extends AbstractHelper
{
    
    protected $format = array(
        'date'     => 'Y-m-d',
        'time'     => 'H:i:s',
        'datetime' => 'Y-m-d H:i:s',
        'default'  => 'Y-m-d H:i:s',
    );
    
    public function __construct(array $format = array())
    {
        $this->format = array_merge($this->format, $format);
    }
    
    /**
     * 
     * Outputs a formatted timestamp using date() format codes.
     * 
     * @param string $spec Any date-time string suitable for
     * strtotime().
     * 
     * @param string $format An optional custom date() formatting string.
     * 
     * @return string The formatted date string.
     * 
     */
    public function __invoke($spec, $format = 'default')
    {
        if (isset($this->format[$format])) {
            $format = $this->format[$format];
        }
        $time = strtotime($spec);
        return $this->escape(date($format, $time));
    }
}
