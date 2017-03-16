<?php
declare(strict_types = 1);
/**
 * @copyright 2016-2017 Hostnet B.V.
 */

/**
 * @covers \Hostnet_Sniffs_Classes_VariableAndPropertyMustBeInSnakeCaseSniff

 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Tests_Classes_VariableAndPropertyMustBeInSnakeCaseUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return [int => int]
     */
    public function getErrorList()
    {
        return [
            6 => 1,
            8 => 1,
            10 => 1,
            14 => 1,
            16 => 1,
            18 => 1,
            21 => 1,
            23 => 1,
            25 => 1,
            38 => 1,
            39 => 1,
            40 => 1
        ];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return [int => int]
     */
    public function getWarningList()
    {
        return [];
    }
}
