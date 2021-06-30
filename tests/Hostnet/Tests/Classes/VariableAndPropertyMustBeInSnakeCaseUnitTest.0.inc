<?php

class MyClass
{
    private $good_property;
    private $badProperty;
    private $goodproperty;
    private $badproperty_;
    private $g;
    private $_badproperty;

    public function setter(
        $good_variable,
        $badVariable,
        $goodvariable,
        $badvariable_,
        $g,
        $_badvariable
    ) {
        $this->good_property  = $good_variable;
        $this->badProperty    = $badVariable;
        $this->goodproperty   = $goodvariable;
        $this->badproperty_   = $badvariable_;
        $this->g              = $g;
        $this->_badproperty   = $_badvariable;
        $dummy = $GLOBALS;
        $dummy = $_SERVER;
        $dummy = $_GET;
        $dummy = $_POST;
        $dummy = $_FILES;
        $dummy = $_COOKIE;
        $dummy = $_SESSION;
        $dummy = $_REQUEST;
        $dummy = $_ENV;
        SomeVendorClass::$badVariable
        SomeVendorClass::$badvariable_
        SomeVendorClass::$_badvariable
        $someVendorObject->$badVariable
        $someVendorObject->$badvariable_
        $someVendorObject->$_badvariable
        $some_vendor_object->$badVariable
        $some_vendor_object->$badvariable_
        $some_vendor_object->$_badvariable
    }
}
