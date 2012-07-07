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
 * Provides a TwoStepView pattern implementation; this allows for an inner 
 * ("core") view and and outer ("layout") view as commonly used by web apps.
 * 
 * @package Aura.View
 * 
 */
class TwoStep
{
    /**
     * 
     * An array of Accept headers, in the format ['type' => weight].
     * 
     * @var array
     * 
     */
    protected $accept;
    
    /**
     * 
     * The .format to render.
     * 
     * @var string
     * 
     */
    protected $format;
    
    /**
     * 
     * A FormatTypes object to map .format values to Content-Type values.
     * 
     * @var FormatTypes
     * 
     */
    protected $format_types;
    
    /**
     * 
     * The data for the inner view.
     * 
     * @var array
     * 
     */
    protected $inner_data = [];
    
    /**
     * 
     * The paths to search when finding an inner view template.
     * 
     * @var array
     * 
     */
    protected $inner_paths = [];
    
    /**
     * 
     * The inner view to use. This may be one of three types:
     * 
     * - (string) A template file name.
     * 
     * - (callable) A callable to execute. It should take no parameters.
     * 
     * - (array) An array where each element key is a .format name, and the
     *   corresponding element value is a string or a callable. This type is
     *   most useful when allowing for multiple views using the same data.
     * 
     * @var mixed
     * 
     */
    protected $inner_view;
    
    /**
     * 
     * The name of the variable in the outer view template that should be
     * replaced with the output of the inner view template.
     * 
     * @var string
     * 
     */
    protected $inner_view_var = 'inner_view';
    
    /**
     * 
     * The Template object to be used when rendering the inner view and outer
     * view.
     * 
     * @var Template
     * 
     */
    protected $template;
    
    /**
     * 
     * The data for the outer view.
     * 
     * @var array
     * 
     */
    protected $outer_data = [];
    
    /**
     * 
     * The paths to search when finding the outer view template.
     * 
     * @var array
     * 
     */
    protected $outer_paths = [];
    
    /**
     * 
     * The inner view to use. This may be one of three types:
     * 
     * - (string) A template file name.
     * 
     * - (callable) A callable to execute; it should be a single parameter,
     *   `$inner`, which is the content of the inner view.
     * 
     * - (array) An array where each element key is a .format name, and the
     *   corresponding element value is a string or a callable. This type is
     *   most useful when allowing for multiple views using the same data.
     * 
     * @var mixed
     * 
     */
    protected $outer_view;
    
    /**
     * 
     * Constructor.
     * 
     * @param Template $template The Template object to be used when rendering
     * the inner view and outer view.
     * 
     * @param FormatTypes $format_types format types
     * 
     */
    public function __construct(Template $template, FormatTypes $format_types)
    {
        $this->template = $template;
        $this->format_types = $format_types;
    }
    
    /**
     * 
     * Set the Accept values for negotiating formats.
     * 
     * @param array $accept An array where each key is a content-type and each
     * value is a corresponding weight.
     * 
     * @return void
     * 
     */
    public function setAccept(array $accept = [])
    {
        $this->accept = $accept;
    }
    
    /**
     * 
     * Returns the Accept values for negotiating formats.
     * 
     * @return array
     * 
     */
    public function getAccept()
    {
        return $this->accept;
    }
    
    /**
     * 
     * Sets the .format to pick when the inner/outer views provide multiple
     * formats.
     * 
     * @param string
     * 
     * @return void
     * 
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    /**
     * 
     * Returns the .format to pick when the inner/outer views provide multiple
     * formats.
     * 
     * @return string
     * 
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * 
     * Returns the Content-Type for the current .format, if any.
     * 
     * @return string
     * 
     */
    public function getContentType()
    {
        return $this->format_types->getContentType($this->format);
    }
    
    /**
     * 
     * Sets inner view specification. The specification may be:
     * 
     * - (string) A template file name.
     * 
     * - (callable) A closure to execute; it should take no parameters.
     * 
     * - (array) An array where each element key is a .format name, and the
     *   corresponding element value is a string or a callable. This type is
     *   most useful when allowing for multiple views using the same data.
     * 
     * @param mixed $inner_view The inner view specification.
     * 
     * @return void
     * 
     */
    public function setInnerView($inner_view)
    {
        $this->inner_view = $inner_view;
    }
    
    /**
     * 
     * Returns the inner view specification.
     * 
     * @param string $format Only return the specification for this .format;
     * if null, return all formats.
     * 
     * @return mixed
     * 
     */
    public function getInnerView($format = null)
    {
        return $this->getView($this->inner_view, $format);
    }
    
    /**
     * 
     * Sets the data for the inner view.
     * 
     * @param array $inner_data The data for the inner view.
     * 
     * @return void
     * 
     */
    public function setInnerData($inner_data)
    {
        $this->inner_data = $inner_data;
    }
    
    /**
     * 
     * Returns the data for the inner view.
     * 
     * @return array $inner_data The data for the inner view.
     * 
     */
    public function getInnerData()
    {
        return $this->inner_data;
    }
    
    /**
     * 
     * Sets the paths to search when finding the inner view template.
     * 
     * @param array $inner_paths The paths to search when finding the inner 
     * view template.
     * 
     * @return void
     * 
     */
    public function setInnerPaths(array $inner_paths = [])
    {
        $this->inner_paths = $inner_paths;
    }
    
    /**
     * 
     * Appends a path to search when finding the inner view template.
     * 
     * @param string $path The path to append.
     * 
     * @return void
     * 
     */
    public function addInnerPath($path)
    {
        $this->inner_paths[] = $path;
    }
    
    /**
     * 
     * Returns the paths to search when finding the inner view template.
     * 
     * @return array
     * 
     */
    public function getInnerPaths()
    {
        return $this->inner_paths;
    }
    
    /**
     * 
     * Sets the outer view specification. The specification may be:
     * 
     * - (string) A template file name.
     * 
     * - (callable) A callable to execute; it should take one parameter,
     *   `$inner`, which is the content of the inner view.
     * 
     * - (array) An array where each element key is a .format name, and the
     *   corresponding element value is a string or a callable. This type is
     *   most useful when allowing for multiple views using the same data.
     * 
     * @param mixed $outer_view The outer view specification.
     * 
     * @return void
     * 
     */
    public function setOuterView($outer_view)
    {
        $this->outer_view = $outer_view;
    }
    
    /**
     * 
     * Returns the outer view specification.
     * 
     * @param string $format Only return the specification for this .format;
     * if null, return all formats.
     * 
     * @return mixed
     * 
     */
    public function getOuterView($format = null)
    {
        return $this->getView($this->outer_view, $format);
    }
    
    /**
     * 
     * Sets the data for the outer view.
     * 
     * @param array $outer_data The data for the outer view.
     * 
     * @return void
     * 
     */
    public function setOuterData($outer_data)
    {
        $this->outer_data = $outer_data;
    }
    
    /**
     * 
     * Returns the data for the outer view.
     * 
     * @return array
     * 
     */
    public function getOuterData()
    {
        return $this->outer_data;
    }
    
    /**
     * 
     * Sets the paths to search when finding the outer view template.
     * 
     * @param array $outer_paths The paths to search when finding the outer 
     * view template.
     * 
     * @return void
     * 
     */
    public function setOuterPaths(array $outer_paths = [])
    {
        $this->outer_paths = $outer_paths;
    }
    
    /**
     * 
     * Appends a path to search when finding the outer view template.
     * 
     * @param string $path The path to append.
     * 
     * @return void
     * 
     */
    public function addOuterPath($path)
    {
        $this->outer_paths[] = $path;
    }
    
    /**
     * 
     * Returns the paths to search when finding the outer view template.
     * 
     * @return array
     * 
     */
    public function getOuterPaths()
    {
        return $this->outer_paths;
    }
    
    /**
     * 
     * Sets the name of the variable in the outer view template that should 
     * be replaced with the output of the inner view template.
     * 
     * @param string $inner_view_var The variable name in the outer view
     * template.
     * 
     * @return void
     * 
     */
    public function setInnerViewVar($inner_view_var)
    {
        $this->inner_view_var = $inner_view_var;
    }
    
    /**
     * 
     * Returns the name of the variable in the outer view template that should 
     * be replaced with the output of the inner view template.
     * 
     * @return string
     * 
     */
    public function getInnerViewVar()
    {
        return $this->inner_view_var;
    }
    
    /**
     * 
     * Renders the inner view and outer view and returns the resulting output,
     * negotiating a format from the accept header values.
     * 
     * @return string The rendered two-step view results.
     * 
     */
    public function render()
    {
        // if format is not set, pick a format to render based on Accept types
        if (! $this->format && $this->accept) {
            $accept = array_keys($this->accept);
            $formats = array_keys((array) $this->inner_view);
            $this->format  = $this->format_types->matchAcceptFormats(
                $accept,
                $formats
            );
        }
        
        // render inner view
        $inner = $this->renderInnerView();
        
        // render outer view, and done
        return $this->renderOuterView($inner);
    }
    
    /**
     * 
     * Renders the inner view.
     * 
     * @return string
     * 
     */
    public function renderInnerView()
    {
        $view = $this->getInnerView($this->format);
        switch (true) {
        case (! $view):
            // no view
            $inner = null;
            break;
        case (is_callable($view)):
            // view is a callable
            $inner = $view();
            break;
        default:
            // view is a string path to a template
            $this->template->setData($this->inner_data);
            $this->template->setPaths($this->inner_paths);
            $inner = $this->template->fetch($view);
            break;
        }
        return $inner;
    }
    
    /**
     * 
     * Renders the outer view.
     * 
     * @param string $inner The output from the inner view.
     * 
     * @return string
     * 
     */
    public function renderOuterView($inner)
    {
        $view = $this->getOuterView($this->format);
        switch (true) {
        case (! $view):
            // no view
            $outer = $inner;
            break;
        case (is_callable($view)):
            // view is a callable
            $outer = $view($inner);
            break;
        default:
            // view is a string path to a template
            $this->outer_data[$this->inner_view_var] = $inner;
            $this->template->addData($this->outer_data);
            $this->template->setPaths($this->outer_paths);
            $outer = $this->template->fetch($view);
            break;
        }
        return $outer;
    }
    
    // FIXME $format
    /**
     * 
     * Gets the view for a particular format.
     * 
     * @param mixed $view The inner or outer view specification.
     * 
     * @param string $format
     * 
     * @return mixed The matching view for the format.
     * 
     */
    protected function getView($view, $format)
    {
        // is the view empty?
        if (! $view) {
            return null;
        }
        
        // is a format specified?
        if ($format === null) {
            return $view;
        }
        
        // is the view anything besides an array?
        if (! is_array($view)) {
            // not an array, return as-is
            return $view;
        }
        
        // the view is an array, look for a matching content-type
        if (isset($view[$format])) {
            return $view[$format];
        }
        
        // no match
        return false;
    }
}
