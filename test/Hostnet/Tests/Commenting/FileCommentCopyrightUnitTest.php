<?php

/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * Unit-test for the FileCommentCopyrightSniff.
 */
class Hostnet_Tests_Commenting_FileCommentCopyrightUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
{

    /**
     * Returns the lines where error should occur, non in our case.
     *
     * @return array empty list.
     */
    public function getErrorList($filename = '')
    {
        switch ($filename) {
            case 'FileCommentCopyrightUnitTest.7.inc':
                return [
                    2 => 1
                ];
            case 'FileCommentCopyrightUnitTest.9.inc':
                return [
                    3 => 1
                ];
            default:
                return [];
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
    public function getWarningList($filename = '')
    {
        switch ($filename) {
            case 'FileCommentCopyrightUnitTest.1.inc':
                return [
                    1 => 2
                ];
            case 'FileCommentCopyrightUnitTest.2.inc':
                return [
                    1 => 1,
                    3 => 1
                ];
            case 'FileCommentCopyrightUnitTest.3.inc':
                return [
                    1 => 1,
                    5 => 1
                ];
            case 'FileCommentCopyrightUnitTest.4.inc':
                return [
                    1 => 1,
                    2 => 1
                ];
            case 'FileCommentCopyrightUnitTest.5.inc':
                return [
                    2 => 1
                ];
            case 'FileCommentCopyrightUnitTest.6.inc':
                return [
                    1 => 1
                ];
            case 'FileCommentCopyrightUnitTest.7.inc':
                return []
                ;
            case 'FileCommentCopyrightUnitTest.8.inc':
                return [
                    3 => 1,
                    4 => 1
                ];
            case 'FileCommentCopyrightUnitTest.9.inc':
                return [
                    4 => 1
                ];
            case 'FileCommentCopyrightUnitTest.10.inc':
                return [
                    1 => 1,
                    4 => 1
                    ];
            default:
                return [];
        }
    }
}
