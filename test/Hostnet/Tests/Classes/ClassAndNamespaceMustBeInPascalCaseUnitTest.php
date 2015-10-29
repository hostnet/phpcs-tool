<?php

/**
 * Unit test for ClassAndNamespaceMustBeInPascalCaseSniff.
 *
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Tests_Classes_ClassAndNamespaceMustBeInPascalCaseUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $filename
     *
     * @return [int => int]
     */
    public function getErrorList()
    {
        return [];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return [int => int]
     */
    public function getWarningList($filename = null)
    {
        switch ($filename) {
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.0.inc':
                return [2 => 4];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.1.inc':
                return [4 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.2.inc':
                return [2 => 3];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.3.inc':
                return [4 => 1];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.4.inc':
                return [];
            case 'ClassAndNamespaceMustBeInPascalCaseUnitTest.5.inc':
                return [3 => 1];
        }
    }
}
