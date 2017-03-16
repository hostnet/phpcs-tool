<?php
/**
 * @covers stdClass
 */
class MyClass
{

    private $bar;

    protected $foo;

    public function find()
    {
        $a_variable = 1;
        return $a_variable;
    }

    protected $bleh;

    /**
     * @covers /Hostnet\Test\TestUnit\Good
     */
    /**
     * @covers Hostnet\Test\TestUnit\Good
     */
    const COMPLICATED = 'public';

}