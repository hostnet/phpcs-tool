<?php
/**
 * @covers Hostnet\Test\TestUnit\Good
 */
class MyClass
{
    // @covers not used here
    private $bar;

    /**
     * @covers stdClass
     */
    protected $foo;

    /**
     * @covers Hostnet\Test\TestUnit\Good
     */
    public function find()
    {
        $a_variable = 1;
        return $a_variable;
    }

    protected $bleh;

    /**
     * @coversNothing
     */
    const COMPLICATED = 'public';

}
