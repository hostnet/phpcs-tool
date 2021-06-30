<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Commenting;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \/Hostnet/Sniffs/Commenting/AtCoversFullyQualifiedNameSniff
 */
class AtCoversFullyQualifiedNameUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
     */
    public function getErrorList($filename = '')
    {
        $list = [
            'AtCoversFullyQualifiedNameUnitTest.php.0.inc' => [
                3  => 1,
                11 => 1,
                16 => 1,
            ],
        ];

        if (! isset($list[$filename])) {
            return [];
        }
        return $list[$filename];
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
        return [];
    }
}
