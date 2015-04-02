<?php
namespace Hostnet\Component\CodeSniffer;

/**
 * This class extends the PHP CodeSniffer CLI class by adding the 'phpcs.xml.dist' feature..
 *
 * It looks if the phpcs.xml.dist is there and copies it to the phpcs.xml while running and
 * when shutting down it removes the phpcs.xml only if this class is responsible for copying.
 *
 * This class also set's some defaults, like running through PHP files only.
 *
 * @author Stefan Lenselink <slenselink@hostnet.nl>
 */
class HostnetCLI extends \PHP_CodeSniffer_CLI
{

    /**
     * Is the phpcs.xml.dist copied?
     *
     * @var boolean
     */
    private $copied = false;

    /**
     * Validate if the given standard is correct, this function only kicks in when the 'default'
     * needs to be loaded.
     *
     * @see PHP_CodeSniffer_CLI::validateStandard()
     * @param array $standards the requested standard or null when defaulting.
     * @return array of loaded standard
      */
    public function validateStandard($standards)
    {
        if ($standards === null) {
            // They did not supply a standard to use.
            // Looks for a ruleset in the current directory.
            $default_dist = getcwd() . DIRECTORY_SEPARATOR . 'phpcs.xml.dist';
            $default      = getcwd() . DIRECTORY_SEPARATOR . 'phpcs.xml';

            if (is_file($default_dist) === true) {
                if (!is_file($default)) {
                    $this->copied = copy($default_dist, $default);
                }
                return array(
                        $default
                );
            }
        }
        return parent::validateStandard($standards);
    }

    /**
     * Get the the hostnet-based 'defaults' list:
     * - Only run .php files.
     *
     * @see PHP_CodeSniffer_CLI::getDefaults()
     * @return array with defaults.
     */
    public function getDefaults()
    {
        $values = parent::getDefaults();
        if (!count($values['extensions'])) {
            $values['extensions'][] = 'php';
        }
        return $values;
    }

    /**
     * Turn-down class, removing the copied phpcs.xml if we done so.
     */
    public function __destruct()
    {
        if ($this->copied) {
            unlink(getcwd() . DIRECTORY_SEPARATOR . 'phpcs.xml');
        }
    }
}
