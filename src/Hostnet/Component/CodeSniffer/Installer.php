<?php
declare(strict_types=1);
/**
 * @copyright 2017-2018 Hostnet B.V.
 */

namespace Hostnet\Component\CodeSniffer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Hostnet\Component\Path\Path;

/**
 * This small composer installer plugin hooks into the post-autoload-dump
 * and configures the Hostnet standard for PHPCS
 */
class Installer implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var IOInterface
     **/
    private $io;

    /**
     * Ugly Composer static call to hook in to the post-autoload-dump event.
     *
     * @return array holding the post-autoload-dump hook and function to execute.
     */
    public static function getSubscribedEvents()
    {
        return ['post-autoload-dump' => 'execute'];
    }

    /**
     * Configuration for standalone use in a system wide installation scenario
     */
    public static function configureAsRoot()
    {
        $vendor_dir = Path::VENDOR_DIR . '/hostnet/phpcs-tool/src';
        if (!file_exists($vendor_dir)) {
            self::configure();
            mkdir($vendor_dir . '/Hostnet', 0777, true);
            symlink(__DIR__ . '/../../Sniffs', $vendor_dir . '/Hostnet/Sniffs');
            copy(__DIR__ . '/../../ruleset.xml', $vendor_dir . '/Hostnet/ruleset.xml');

            mkdir($vendor_dir . '/HostnetPaths', 0777, true);
            symlink(__DIR__ . '/../../../HostnetPaths/Sniffs', $vendor_dir . '/HostnetPaths/Sniffs');
            copy(__DIR__ . '/../../../HostnetPaths/ruleset.xml', $vendor_dir . '/HostnetPaths/ruleset.xml');
        }
    }

    /**
     * Activate this plugin by storing the bin_dir.
     *
     * @see \Composer\Plugin\PluginInterface::activate()
     * @param Composer    $composer the composer instance to pull the config of the bin-dir from.
     * @param IOInterface $io the io interface to write / read output input.
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * The 'logical'-operation of this Installer.
     * PHPCS does not define constants for the config options,
     * doing so ourselves would only lead to outdated intel.
     *
     * @see https://github.com/squizlabs/PHP_CodeSniffer/wiki/Configuration-Options
     */
    public function execute()
    {
        if ($this->io->isVerbose()) {
            $this->io->write('Configured phpcs to use Hostnet standard');
        }
        self::configure();
    }

    /**
     * Configure the Hostnet code style
     */
    public static function configure()
    {
        $config = [
            'colors'          => '1',
            'installed_paths' => Path::VENDOR_DIR . '/hostnet/phpcs-tool/src/',
        ];

        if (!file_exists('phpcs.xml') && !file_exists('phpcs.xml.dist')) {
            $config['default_standard'] = 'Hostnet';
        }

        $file = Path::VENDOR_DIR . '/squizlabs/php_codesniffer/CodeSniffer.conf';
        file_put_contents($file, sprintf('<?php $phpCodeSnifferConfig = %s;', var_export($config, true)));

        $tags = '';
        $dirs = ['src', 'test', 'tests'];
        foreach ($dirs as $dir) {
            $path = Path::BASE_DIR . '/' . $dir . '/';
            if (is_dir($path)) {
                $tags .= '<file>' . $path . '</file>';
            }
        }

        $xml  = <<<XML
<?xml version="1.0"?>
<ruleset name="HostnetPaths">
    <description>Generated file with directories to scan</description>
    <!-- Hostnet Default path settings -->
    <exclude-pattern>*/Generated/*</exclude-pattern>
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*\.js</exclude-pattern>
    <exclude-pattern>*\.css</exclude-pattern>

    $tags
</ruleset>
XML;
        $dir  = __DIR__ . '/../../../HostnetPaths';
        $file = $dir . '/ruleset.xml';

        if (!is_dir($dir)) {
            mkdir($dir);
        }
        file_put_contents($file, $xml);
    }
}
