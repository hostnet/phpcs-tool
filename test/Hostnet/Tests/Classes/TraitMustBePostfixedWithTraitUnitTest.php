<?php
/**
 * @copyright 2016-2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\TraitMustBePostfixedWithTraitSniff
 */
class TraitMustBePostfixedWithTraitUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $filename
     *
     * @return [int => int]
     */
    public function getErrorList($filename = null)
    {
        switch ($filename) {
            case 'TraitMustBePostfixedWithTraitUnitTest.0.inc':
                return [];
            case 'TraitMustBePostfixedWithTraitUnitTest.1.inc':
                return [];
            case 'TraitMustBePostfixedWithTraitUnitTest.2.inc':
                return [3 => 1];
            case 'TraitMustBePostfixedWithTraitUnitTest.3.inc':
                return [];
        }
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $filename
     *
     * @return [int => int]
     */
    public function getWarningList($filename = null)
    {
        return [];
    }
}
