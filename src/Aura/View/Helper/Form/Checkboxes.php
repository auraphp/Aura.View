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
namespace Aura\View\Helper\Form;

use Aura\View\Helper\AbstractHelper;
use Aura\View\Helper\Form\Input\Checked;

/**
 *
 * Helper for series of `<input type="checkbox">` tags.
 *
 * @package Aura.View
 *
 */
class Checkboxes extends AbstractHelper
{
    /**
     *
     * Constructor.
     *
     * @param Checked $input A checked-input helper for generating buttons.
     *
     */
    public function __construct(Checked $input)
    {
        $this->input = $input;
    }

    /**
     *
     * Returns multiple checkbox input fields.
     *
     * @param array  $attribs   The base attributes for the checkboxes.
     *
     * @param array  $options   The checkbox values and labels.
     *
     * @param array   $checked   Which checkbox value should be checked.
     *
     * @param string $separator The separator string to use between each checkbox.
     *
     * @return string
     *
     */
    public function __invoke(
        $attribs,
        $options,
        $checked = null,
        $separator = null
    ) {

        $input = $this->input;
        $attribs['type'] = 'checkbox';
        $html = '';
        foreach ($options as $value => $label) {
            $attribs['value'] = $value;
            $attribs['label'] = $label;
            if (in_array($value, $checked, true)) {
                $html .= $input($attribs, $value) . $separator;
            } else {
                $html .= $input($attribs, '') . $separator;
            }
        }
        return $html;
    }

    /**
     *
     * Returns the input field HTML based on a field specification.
     *
     * @param array $spec A field specification.
     *
     * @return string
     *
     */
    public function getField($spec)
    {
        $attribs = isset($spec['attribs'])
                 ? $spec['attribs']
                 : [];

        $options = isset($spec['options'])
                 ? $spec['options']
                 : [];

        $value = isset($spec['value'])
               ? $spec['value']
               : null;

        if (isset($spec['name'])) {
            $attribs['name'] = $spec['name'];
        }

        return $this->__invoke($attribs, $options, $value);
    }
}
