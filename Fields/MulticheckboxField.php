<?php

namespace Gregwar\DSD\Fields;

/**
 * Checkboxs
 *
 * @author Grégoire Passault <g.passault@gmail.com>
 */
class MulticheckboxField extends Field
{
    /**
     * Nom de la source
     */
    private $source;

    /**
     * Checkboxes
     */
    private $checkboxes = array();

    /**
     * Labels
     */
    private $labels = array();

    /**
     * Sauvegarde des push
     */
    private $pushSave = array();

    public function check()
    {
        return;
    }

    public function push($var, $val)
    {
        switch ($var) {
        case 'source':
            $this->source = $val;
            break;
        default:
            parent::push($var,$val);
            break;
        }
    }

    public function getSource()
    {
        return $this->source;
    }

    public function source($datas)
    {
        foreach ($datas as $value => $label) {
            $this->checkboxes[] = $checkbox = new CheckboxField;
            $checkbox->push('name', $this->nameFor($value));
            $checkbox->push('optional', null);
            $checkbox->push('value', '1');
            $this->labels[$this->nameFor($value)] = $label;

            foreach ($this->pushSave as $var => $val) {
                $checkbox->push($var, $val);
            }
        }
    }

    private function nameFor($name) 
    {
        return $this->getName().'['.$name.']';
    }

    public function setValue($values)
    {
        if (!is_array($values)) {
            return;
        }

        $checked = array();

        foreach ($values as $name) {
            $checked[$this->nameFor($name)] = true;
        }

        foreach ($this->checkboxes as $checkbox) {
            if (isset($checked[$checkbox->getName()])) {
                $checkbox->setChecked(true);
            } else {
                $checkbox->setChecked(false);
            }
        }
    }

    public function getValue()
    {
        $values = array();
        foreach ($this->checkboxes as $checkbox) {
            if ($checkbox->isChecked()) {
                $values[] = $checkbox->getValue();
            }
        }
        return $values;
    }

    public function getHtml()
    {
        $html = '';

        if ($this->checkboxes) {
            foreach ($this->checkboxes as $checkbox) {
                $html.= '<div class="'.$this->getAttribute('class').'" />';
                $html.= '<label>';
                $html.= $checkbox->getHtml();
                $html.= $this->labels[$checkbox->getName()];
                $html.= '</label>';
                $html.= '</div>';
            }
        }

        return $html;
    }
}
