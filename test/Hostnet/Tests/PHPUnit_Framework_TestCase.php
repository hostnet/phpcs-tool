<?php
/**
 * @copyright 2017 Hostnet B.V.
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

// @codingStandardsIgnoreStart
// Ugly polyfill for supporting PHP6.0 Since PHPCS
// still wants to run on PHPUnit ~4.0

class PHPUnit_Framework_TestCase extends TestCase
// @codingStandardsIgnoreEnd
{
    public function doesNotPerformAssertions()
    {
        return true;
    }
}
