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

/**
 * 
 * Helper for a formatted timestamp using date() format codes.
 * 
 * Default format is "Y-m-d H:i:s".
 * 
 * @package Aura.View
 * 
 */
class Datetime extends AbstractHelper
{
    /**
     * 
     * An array of datetime formats.
     * 
     * @var array
     * 
     */
    protected $format = [
        'date'     => 'Y-m-d',
        'time'     => 'H:i:s',
        'datetime' => 'Y-m-d H:i:s',
        'default'  => 'Y-m-d H:i:s',
    ];

    /**
     * 
     * Constructor.
     * 
     * @param array $format Additional or override datetime formats.
     * 
     */
    public function __construct(array $format = [])
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
        return date($format, $time);
    }
}
