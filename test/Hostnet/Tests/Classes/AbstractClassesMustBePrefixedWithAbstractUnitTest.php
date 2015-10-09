<?php

/**
 * Unit test for AbstractClassesMustBePrefixedWithAbstractSniff
 *
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Tests_Classes_AbstractClassesMustBePrefixedWithAbstractUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
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
            case 'AbstractClassesMustBePrefixedWithAbstractUnitTest.0.inc':
                return [];
            case 'AbstractClassesMustBePrefixedWithAbstractUnitTest.1.inc':
                return [];
            case 'AbstractClassesMustBePrefixedWithAbstractUnitTest.2.inc':
                return [3 => 1];
            case 'AbstractClassesMustBePrefixedWithAbstractUnitTest.3.inc':
                return [];
        }
    }
}
