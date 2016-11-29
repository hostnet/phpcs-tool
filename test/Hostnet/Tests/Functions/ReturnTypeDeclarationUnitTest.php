<?php
/**
 * Unit test class for the ReturnTypeDeclaration sniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Unit test class for the ReturnTypeDeclaration sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Arent van Korlaar <avkorlaar@hostnet.nl>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Hostnet_Tests_Functions_ReturnTypeDeclarationUnitTest extends Hostnet_Tests_AbstractPHPCSBridge
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
                return array(
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                    65 => 1,
                );
            case 'ReturnTypeDeclarationUnitTest.2.inc':
                return array(
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                    65 => 1,
                );
            case 'ReturnTypeDeclarationUnitTest.3.inc':
                return array(
                    14 => 1,
                    16 => 1,
                    18 => 1,
                    20 => 1,
                    23 => 1,
                    26 => 1,
                    29 => 1,
                    33 => 1,
                    37 => 1,
                    41 => 1,
                );
            case 'ReturnTypeDeclarationUnitTest.4.inc':
                return array(
                    17 => 1,
                    22 => 1,
                    27 => 1,
                    32 => 1,
                    37 => 1,
                    42 => 1,
                    47 => 1,
                    53 => 1,
                    59 => 1,
                    65 => 1,
                );
            default:
                return array();
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
        return array();
    }//end getWarningList()
}//end class
