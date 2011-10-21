<?php
/**
 * 
 * Helper to generate route
 * 
 * @package Aura.View
 * 
 */
namespace Aura\View\Helper;
use Aura\View\Helper\AbstractHelper;
use Aura\Router\Map;
class Route extends AbstractHelper
{
    /*
     * object of Aura\Router\Map
     */
    protected $router_map;
    
    /*
     * Constructor level injection setting Aura\Router\Map object
     */
    public function __construct(Map $router_map)
    {
        $this->router_map = $router_map;
    }
    
    /**
     * 
     * Returns a route by name, and interpolates data into it to 
     * return a URI path.
     * 
     * @param string $name The route name to look up.
     * 
     * @param array $data The data to inpterolate into the URI; data keys
     * map to param tokens in the path.
     * 
     * @return string|false A URI path string if the route name is found, or
     * boolean false if not.
     * 
     */
    public function __invoke($route_name, array $data = array())
    {
        return $this->router_map->generate($route_name, $data);
    }
}
