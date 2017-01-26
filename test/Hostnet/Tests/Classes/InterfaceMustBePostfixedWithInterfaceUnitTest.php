<?php
/**
 * @copyright 2015-2017 Hostnet B.V.
 */

/**
 * @covers Hostnet_Sniffs_Classes_InterfaceMustBePostfixedWithInterfaceSniff
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Tests_Classes_InterfaceMustBePostfixedWithInterfaceUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
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
    public function getErrorList($filename = null)
    {
        switch ($filename) {
            case 'InterfaceMustBePostfixedWithInterfaceUnitTest.0.inc':
                return [];
            case 'InterfaceMustBePostfixedWithInterfaceUnitTest.1.inc':
                return [];
            case 'InterfaceMustBePostfixedWithInterfaceUnitTest.2.inc':
                return [3 => 1];
            case 'InterfaceMustBePostfixedWithInterfaceUnitTest.3.inc':
                return [];
        }
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
