<?php 

namespace depage\htmlform\elements;

use depage\htmlform\abstracts;

/**
 * The fieldset class holds HTML-fieldset specific attributes and methods.
 **/
class fieldset extends abstracts\container {
    /**
     * Contains reference to current fieldsets' parent HTML form.
     **/
    protected $form;

    /**
     * @param $name string - fieldset name
     * @param $parameters array of fieldset parameters, HTML attributes
     * @return void
     **/
    public function __construct($name, $parameters = array()) {
        parent::__construct($name, $parameters);

        $this->label = (isset($parameters['label'])) ? $parameters['label'] : $this->name; 
    }

    /**
     * sets parent form of fieldset
     *
     * @param $form object - parent form object
     * @return void
     **/
    public function setParentForm($form) {
        $this->form = $form;

        $this->addChildElements();
    }

    /**
     * overridable method to add child elements
     *
     * @return void
     **/
    protected function addChildElements() {
    }

    /** 
     * Calls parent class to generate an input element or a fieldset and add
     * it to its list of elements
     * 
     * @param $type input type or fieldset
     * @param $name string - name of the element
     * @param $parameters array of element attributes: HTML attributes, validation parameters etc.
     * @return object $newInput
     **/
     public function addElement($type, $name, $parameters = array()) {
        $this->form->checkElementName($name);

        $newElement = parent::addElement($type, $name, $parameters);
        
        if ($newElement instanceof fieldset) {
            // if it's a fieldset it needs to know which form it belongs to
            $newElement->setParentForm($this->form);
        } else {
            $this->form->updateInputValue($name);
        }

        return $newElement;
    }

    /**
     * Renders the fieldset as HTML code. If the fieldset contains elements it
     * calls their rendering methods.
     *
     * @return string
     **/
     public function __toString() {
        $renderedElements   = '';
        $formName           = $this->form->getName();
        $label              = $this->htmlLabel();

        foreach($this->elementsAndHtml as $element) {
            $renderedElements .= $element;
        }

        return "<fieldset id=\"{$formName}-{$this->name}\" name=\"{$this->name}\">" .
            "<legend>{$label}</legend>{$renderedElements}" .
        "</fieldset>\n";
    }
}
