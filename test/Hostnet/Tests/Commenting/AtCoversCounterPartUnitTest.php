<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests\Commenting;

use Hostnet\Tests\AbstractSniffUnitTest;

/**
 * @covers \Hostnet\Sniffs\Commenting\AtCoversCounterPartSniff
 */
class AtCoversCounterPartUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList($filename = '')
    {
        $list = [
            'AtCoversCounterPartUnitTest.php.0.inc'  => [],
            'AtCoversCounterPartUnitTest.php.1.inc'  => [],
            'AtCoversCounterPartUnitTest.php.2.inc'  => [
                4  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.3.inc'  => [
                4  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.4.inc'  => [
                7  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.5.inc'  => [
                7  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.6.inc'  => [
                8  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.7.inc'  => [
                6  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.8.inc'  => [
                9  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.9.inc'  => [
                7  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.10.inc' => [
                10  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.11.inc' => [
                10  => 1,
            ],
            'AtCoversCounterPartUnitTest.php.12.inc' => [
                10  => 1,
            ],
        ];

        if (! isset($list[$filename])) {
            return [];
        }
        return $list[$filename];
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningList()
    {
        return [];
    }
}
