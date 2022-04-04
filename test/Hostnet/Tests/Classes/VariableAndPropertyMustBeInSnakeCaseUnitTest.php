<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Classes;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Classes\VariableAndPropertyMustBeInSnakeCaseSniff
 */
class VariableAndPropertyMustBeInSnakeCaseUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     */
    public function getErrorList(): array
    {
        return [
            6  => 1,
            8  => 1,
            10 => 1,
            14 => 1,
            16 => 1,
            18 => 1,
            21 => 1,
            23 => 1,
            25 => 1,
            38 => 1,
            39 => 1,
            40 => 1,
        ];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     */
    public function getWarningList(): array
    {
        return [];
    }
}
