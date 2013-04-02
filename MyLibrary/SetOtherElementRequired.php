<?php

/**
 * Attaches a list of conditions (as validation objects) to an
 *  element, which if they pass, will setRequired(true) on another element.
 */
class MyLibrary_SetOtherElementRequired extends Zend_Validate_Abstract
{
    /**
     * @var array {@link Zend_Validate_Abstract}
     */
    public $_validators = array();

    /**
     * @var Zend_Form_Element
     */
    public $_dependentElement;

    /**
     * If no validators are supplied, not empty is applied.
     * 
     * @param Zend_Form_Element $dependentElement the element on which we conditionally want to be required
     * @param Zend_Validate_NotEmpty $validators a list of validators which the value must pass in order for the dependent element to be set as required
     */
    public function __construct(Zend_Form_Element $dependentElement, $validators = null)
    {
        if (!is_array($validators)) {
            $validators = array(new Zend_Validate_NotEmpty());
        }
        $this->setDependentElement($dependentElement)
                ->setValidators($validators);
    }

    /**
     * @param Zend_Form_Element $dependentElement
     * @return MyLibrary_SetOtherElementRequired
     */
    public function setDependentElement(Zend_Form_Element $dependentElement)
    {
        $this->_dependentElement = $dependentElement;
        return $this;
    }

    /**
     * @return Zend_Form_Element
     */
    public function getDependentElement()
    {
        return $this->_dependentElement;
    }

    /**
     * @param array $validators
     * @return MyLibrary_SetOtherElementRequired
     */
    public function setValidators(array $validators)
    {
        $this->_validators = $validators;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->_validators;
    }

    /**
     * @param string value
     * @see Zend_Validate_Interface::isValid()
     */
    public function isValid($value)
    {
        $passed = true;
        foreach ($this->getValidators() as $validator) {
            /* @var $validator Zend_Validate_Abstract */
            if (!$validator->isValid($value)) {
                //return true because this isn't really a validator, so we don't want to tell zend it failed
                $passed = false;
                break;
            }
        }
        if ($passed) {
            //all the validators passed, so set required
            $this->getDependentElement()->setRequired(true);
        }
        return true;
    }


}
