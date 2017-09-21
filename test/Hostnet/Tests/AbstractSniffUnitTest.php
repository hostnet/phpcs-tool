<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest as AbstractSniffUnitTestCodeSniffer;
use PHP_CodeSniffer\Tests\Standards\AllSniffs;

/**
 * This class acts as a bridge between our unit-tests and the PHPCodeSniffer Unit-Testing framework.
 * It setup the environment; loading our 'code standard' etc.
 */
abstract class AbstractSniffUnitTest extends AbstractSniffUnitTestCodeSniffer
{
    /**
     * Prepare our code standard setup, PHP Code Sniffer performs this in AllTests
     *
     * @see AbstractSniffUnitTest::setUp()
     */
    protected function setUp()
    {
        new AllSniffs();

        $GLOBALS['PHP_CODESNIFFER_RULESET']                         = new Ruleset(new Config());
        $GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][get_class($this)] = __DIR__ . '/../../../src/Hostnet';
        $GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][get_class($this)]     = __DIR__ . '/';
        $GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']                     = [];
        $GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES']                   = [];

        parent::setUp();
    }

    public function doesNotPerformAssertions()
    {
        return true;
    }
}
