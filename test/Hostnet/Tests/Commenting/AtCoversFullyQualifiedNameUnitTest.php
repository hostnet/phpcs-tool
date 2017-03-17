<?php
declare(strict_types = 1);
/**
 * @copyright 2017 Hostnet B.V.
 */
/**
 * @covers /Hostnet/Sniffs/Commenting/AtCoversFullyQualifiedNameSniff
 */
class Hostnet_Tests_Commenting_AtCoversFullyQualifiedNameUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
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
            ]
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
        return array();
    }
}
