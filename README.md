Zend Form Validator for making an element mandatory based on another element
======================

Lets get our BDD on
------
As a developer, I want a way to set element 'y' as required, if and only if element 'x' has a value.

Scenarios
------
    Given that I have set validators on element 'y', 
    When I submit the form, 
    And I haven't got any value in field 'x', 
    Then I don't want those validators to run on element 'y'.

And

    Given that I have set validators on element 'y', 
    When I submit the form, 
    And I have got any value in field 'x', 
    Then I want those validators to run on element 'y'.

The Problem With Context in Custom Validators
------
The obvious way to achieve this would be to create a custom validator which checked the 'context' 
param (second one passed to isValid() method), and then internally apply `Zend_Validate_NotEmpty` 
to the value if the context meets our requirements.
E.g.
    <?php
    $foo = new Zend_Form_Element_Text('foo');
    $foo->setRequired(false);
    
    $bar = new Zend_Form_Element_Text('bar');
    $bar->addValidator(new MyCustomValidatorWhichChecksToSeeIfCheckboxIsChecked('foo));
    $bar->setRequired(false);
    ?>

However, standard Zend Framework form validators will not be run unless an element is marked as 'required' or has a value. 
Therefore, you have to be able to set a field to required based on another field being populated. 
But, this can't be attached to a custom validator on the target field because it won't be run unless it is required (and round, and round we go).

The Solution
------
A simple zend form validator which can change field 'y's requiredness if 'x' passes certain validation or not.

So if you have had this problem, simple copy and paste the contents of the only other file in this repo into your ZF project, rename the class and go nuts.

Usage
------
    <?php
    $foo = new Zend_Form_Element_Text('foo');
    $foo->setRequired(false);
    
    $bar = new Zend_Form_Element_Text('bar');
    $bar->setRequired(false);
    
    
    $optionalArrayOfValidators = array(new Zend_Validate_Alpha, new Zend_Validate_NotEmpty);
    // $optionalArrayOfValidators can be ommitted, in which case 'Zend_Validate_NotEmpty' is used.
    $foo->addValidator(new MyLibrary_SetOtherElementRequired($bar, /*optional*/ $optionalArrayOfValidators));
    ?>
    
