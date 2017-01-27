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
        $list = [
            'FileCommentCopyrightUnitTest.1.inc' => [
                1 => 2
            ],
            'FileCommentCopyrightUnitTest.2.inc' => [
                1 => 1
            ],
            'FileCommentCopyrightUnitTest.3.inc' => [
                1 => 1
            ],
            'FileCommentCopyrightUnitTest.4.inc' => [
                1 => 1
            ],
            'FileCommentCopyrightUnitTest.5.inc' => [
                1 => 1,
                2 => 1
            ],
            'FileCommentCopyrightUnitTest.6.inc' => [
                1 => 2
            ],
            'FileCommentCopyrightUnitTest.8.inc' => [
                3 => 1
            ],
            'FileCommentCopyrightUnitTest.10.inc' => [
                1 => 1
            ]
        ];
        if (! isset($list[$filename])) {
            return [];
        }
        return $list[$filename];
    }
}
