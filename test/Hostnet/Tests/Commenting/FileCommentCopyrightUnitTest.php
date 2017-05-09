<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Commenting;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * Unit-test for the FileCommentCopyrightSniff.
 */
class FileCommentCopyrightUnitTest extends AbstractSniffUnitTest
{

    /**
     * Returns the lines where error should occur
     *
     * @return array empty list.
     */
    public function getErrorList($filename = '')
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
            'FileCommentCopyrightUnitTest.7.inc' => [
                2 => 1
            ],
            'FileCommentCopyrightUnitTest.8.inc' => [
                3 => 1
            ],
            'FileCommentCopyrightUnitTest.9.inc' => [
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

    /**
     * Returns the lines where warnings should occur, which should be none!
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getWarningList($filename = '')
    {
        return [];
    }
}
