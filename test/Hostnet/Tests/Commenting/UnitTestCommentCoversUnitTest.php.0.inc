<?php
/**
 * @covers \Hostnet\Test\TestUnit\Good
 */
class MyClass
{

    private $bar;

    /**
     * @covers Hostnet\Test\TestUnit\Wrong
     */
    protected $foo;

    /**
     * @covers \Hostnet\Test\TestUnit\Good
     */
    public function find()
    {
        $a_variable = 1;
        return $a_variable;
    }

    protected $bleh;

    /**
     * @covers Hostnet\Test\TestUnit\Wrong
     */
    const COMPLICATED = 'public';
}