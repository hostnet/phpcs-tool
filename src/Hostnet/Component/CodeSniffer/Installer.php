<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CodeSniffer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Hostnet\Component\Path\Path;
use Symfony\Component\Filesystem\Filesystem;

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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [ScriptEvents::POST_AUTOLOAD_DUMP => 'execute'];
    }

    /**
     * Configuration for standalone use in a system wide installation scenario
     */
    public static function configureAsRoot(): void
    {
        $filesystem = new Filesystem();
        $vendor_dir = Path::VENDOR_DIR . '/hostnet/phpcs-tool/src';
        if ($filesystem->exists($vendor_dir)) {
            return;
        }

        self::configure();

        $filesystem->mkdir($vendor_dir . '/Hostnet');
        $filesystem->symlink(__DIR__ . '/../../Sniffs', $vendor_dir . '/Hostnet/Sniffs');
        $filesystem->copy(__DIR__ . '/../../ruleset.xml', $vendor_dir . '/Hostnet/ruleset.xml');

        $filesystem->mkdir($vendor_dir . '/HostnetPaths');
        $filesystem->copy(__DIR__ . '/../../../HostnetPaths/ruleset.xml', $vendor_dir . '/HostnetPaths/ruleset.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
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
    public function execute(): void
    {
        self::configure();
        if (false === $this->io->isVerbose()) {
            return;
        }

        $this->io->write('<info>Configured phpcs to use Hostnet standard</info>');
    }

    /**
     * Configure the Hostnet code style
     */
    public static function configure(): void
    {
        $filesystem = new Filesystem();
        $config     = [
            'colors'          => '1',
            'installed_paths' => implode(',', [
                Path::VENDOR_DIR . '/hostnet/phpcs-tool/src/',
                Path::VENDOR_DIR . '/slevomat/coding-standard/SlevomatCodingStandard',
                Path::VENDOR_DIR . '/mediawiki/mediawiki-codesniffer/MediaWiki',
            ]),
        ];

        if (!$filesystem->exists(['phpcs.xml', 'phpcs.xml.dist'])) {
            $config['default_standard'] = 'Hostnet';
        }

        $filesystem->dumpFile(
            Path::VENDOR_DIR . '/squizlabs/php_codesniffer/CodeSniffer.conf',
            sprintf('<?php $phpCodeSnifferConfig = %s;', var_export($config, true))
        );

        $filesystem->dumpFile(__DIR__ . '/../../../HostnetPaths/ruleset.xml', self::generateHostnetPathsXml());
    }

    public static function generateHostnetPathsXml(): string
    {
        $tags = '';
        $dirs = ['src', 'test', 'tests'];
        foreach ($dirs as $dir) {
            $path = Path::BASE_DIR . '/' . $dir . '/';
            if (!is_dir($path)) {
                continue;
            }

            $tags .= '<file>' . $path . '</file>';
        }

        return <<<XML
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
    }
}
