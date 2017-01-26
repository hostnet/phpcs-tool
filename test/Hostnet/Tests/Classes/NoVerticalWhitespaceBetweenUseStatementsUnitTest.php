<?php
/**
 * @copyright 2015-2017 Hostnet B.V.
 */
/**
 * @copyright 2015-2017 Hostnet B.V.
 */

class Hostnet_Tests_Classes_NoVerticalWhitespaceBetweenUseStatementsUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
{

    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList($filename = null)
    {
        switch ($filename) {
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.0.inc':
                return array(5 => 1, 12 => 1, 14 => 1, 15 => 1, 16 => 1, 18 => 1);
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.1.inc':
                return array(5 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1);
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.2.inc':
                return array(5 => 1);
            case 'NoVerticalWhitespaceBetweenUseStatementsUnitTest.3.inc':
                return array(4=>1,6 => 1);
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList()
    {
        return array();
    }
}
