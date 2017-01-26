<?php
/**
 * @copyright 2015-2017 Hostnet B.V.
 */

/**
 * Unit test for ProtectedPropertiesAreNotAllowedSniff
 *
 * @author Nico Schoenmaker <nschoenmaker@hostnet.nl>
 */
class Hostnet_Tests_Classes_ProtectedPropertiesAreNotAllowedUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
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
        return array(9 => 1, 23 => 1, 25 => 1, 27 => 1, 30 => 1, 32 => 2);
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
