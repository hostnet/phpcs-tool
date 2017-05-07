<?php
declare(strict_types = 1);

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * @copyright 2017 Hostnet B.V.
 */

// @codingStandardsIgnoreStart
// Class name is prefixed with Abstract,
// but the pear namespaces are not
// recognized.

/**
 * This class acts as a bridge between our unit-tests and the PHPCodeSniffer Unit-Testing framework.
 * It setup the environment; loading our 'code standard' etc.
 *
 * @author Stefan Lenselink <slenselink@hostnet.nl>
 */
abstract class Hostnet_Tests_AbstractPHPCSBridge extends AbstractSniffUnitTest
{
// @codingStandardsIgnoreEnd
    /**
     * Prepare our code standard setup, PHP Code Sniffer performs this in:
     * PHP_CodeSniffer_AllTests and PHP_CodeSniffer_TestSuite
     *
     * @see AbstractSniffUnitTest::setUp()
     */
    protected function setUp()
    {
        $GLOBALS['PHP_CODESNIFFER_CONFIG_DATA']                     = [
            'showSources' => true,
            'colors' => true,
            'default_standard' => 'Hostnet',
            'installed_paths' => __DIR__ . '/../../../src/'
        ];
        $GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][get_class($this)] = __DIR__ . '/../..';
        $GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']                     = [];
        $GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES']                   = [];
        $GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][get_class($this)]     = [__DIR__ . '/..'];

        parent::setUp();
    }

    /**
     * Due to a bug?? our tests where never executed excpet if we specify we want to have
     * 'Show sniff codes in all reports' (-s) option.
     *
     * @see AbstractSniffUnitTest::getCliValues()
     * @param string $filename the file to test.
     * @return the cli arg to load into the code sniffer
     */
    public function getCliValues($filename)
    {
        return ['-s'];
    }
}
