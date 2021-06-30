<?php

$foo = 1;

class MyClass
{
    private $bar;

    protected $foo;

    public $public_variable;

    public function find()
    {
      $a_variable = 1;
      return $a_variable;
    }

    protected function huh()
    {
    }

    protected $bleh;

    protected     $checkWhiteSpace;

    protected
      $checkNewLine;

    protected    /*euauueauaoe*/ $checkComments;

    protected     $multipleVariablesOnOneLine1;     protected     $multipleVariablesOnOneLine2;

    const COMPLICATED = 'public';
}