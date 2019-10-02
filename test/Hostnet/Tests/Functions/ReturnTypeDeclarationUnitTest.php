<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Functions;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Functions\ReturnTypeDeclarationUnitTest
 */
class ReturnTypeDeclarationUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getErrorList($test_file = '')
    {
        switch ($test_file) {
            case 'ReturnTypeDeclarationUnitTest.1.inc':
                return [
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                ];
            case 'ReturnTypeDeclarationUnitTest.2.inc':
                return [
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                ];
            case 'ReturnTypeDeclarationUnitTest.3.inc':
                return [
                    14 => 1,
                    16 => 1,
                    18 => 1,
                    20 => 1,
                    23 => 1,
                    26 => 1,
                    29 => 1,
                    33 => 1,
                    37 => 1,
                ];
            case 'ReturnTypeDeclarationUnitTest.4.inc':
                return [
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                ];
            default:
                return [];
        }//end switch
    }//end getErrorList()

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    public function getWarningList()
    {
        return [];
    }//end getWarningList()
}//end class
