<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\PhpUnit;

use Hostnet\Tests\AbstractSniffUnitTest;

class NamespaceUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array(int => int)
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
     * @param string $filename
     * @return array
     */
    public function getWarningList($filename = null)
    {
        $warnings = [
            'NamespaceUnitTest.0.inc' => [
                3  => 1,
                8 => 1,
            ],
            'NamespaceUnitTest.1.inc' => [
                5 => 1,
            ],
            'NamespaceUnitTest.2.inc' => [
                3 => 1,
            ],
            'NamespaceUnitTest.3.inc' => [
                2 => 1,
                9 => 1,
            ],
        ];

        return $warnings[$filename] ?? [];
    }
}
