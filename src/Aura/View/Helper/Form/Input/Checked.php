<?php
namespace Aura\View\Helper\Form\Input;

class Checked extends Generic
{
    protected function exec()
    {
        // if there's no value to compare to, skip "checked"
        if (isset($this->attribs['value']) && $this->value === $this->attribs['value']) {
            // use strict equality so that there is no confusion between
            // 0/'0'/false/null/''.
            $this->attribs['checked'] = 'checked';
        } else {
            $this->attribs['checked'] = null;
        }
        
        // extract and retain the 'label' pseudo-attribute
        $label = null;
        if (isset($this->attribs['label'])) {
            $label = $this->attribs['label'];
            $this->attribs['label'] = null;
        }
        
        // get the HTML for the input
        $html = parent::exec();
        
        // if no label, we're done
        if (! $label) {
            return $html;
        }
        
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
        
        // return input wrapped by label
        return $html;
    }
}
