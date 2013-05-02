<?php
namespace Aura\View\Helper\Form\Input;

class Checked extends Generic
{
    protected function exec()
    {
        // the HTML to be returned
        $html = '';
        
        // by default, the input is unchecked
        $this->attribs['checked'] = null;
        
        // examine the 'value_unchecked' pseudo-attribute to see if there is
        // there a value when unchecked
        if (isset($this->attribs['value_unchecked'])) {
            $attribs = [
                'type' => 'hidden',
                'value' => $this->attribs['value_unchecked'],
            ];
            $html .= $this->void('input', $attribs);
            // remove the pseudo-attribute
            $this->attribs['value_unchecked'] = null;
        }
        
        // is the input checked? make sure there's a value to compare to, and
        // use strict equality so that there is no confusion between
        // 0/'0'/false/null/''.
        $checked = isset($this->attribs['value'])
                && $this->value === $this->attribs['value'];
        if ($checked) {
            $this->attribs['checked'] = 'checked';
        }
        
        // extract and retain the 'label' pseudo-attribute
        $label = null;
        if (isset($this->attribs['label'])) {
            $label = $this->attribs['label'];
            $this->attribs['label'] = null;
        }
        
        // add the HTML for the input
        $html .= $this->void('input', $this->attribs);
        
        // is there a label?
        if ($label) {
            $attribs = [];
            if (isset($this->attribs['id'])) {
                $attribs['for'] = $this->attribs['id'];
            }
        
            if ($attribs) {
                $attribs = $this->attribs($attribs);
                $html = "<label {$attribs}>{$html} {$label}</label>";
            } else {
                $html = "<label>{$html} {$label}</label>";
            }
        }
        
        // return with indent
        return $this->indent(0, $html);
    }
}
