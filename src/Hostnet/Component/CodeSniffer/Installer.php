<?php
namespace Hostnet\Component\CodeSniffer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Hostnet\Component\Path\Path;

/**
 * This small composer installer plugin hooks into the post-autoload-dump event and swaps out the phpcs and phpcbf
 * files for their hostnet counter parts.
 *
 * @author Stefan Lenselink <slenselink@hostnet.nl>
 */
class Installer implements PluginInterface, EventSubscriberInterface
{
    /**
     * The configured directory of the vendor/bin.
     *
     * @var string
     */
    private $bin_dir;

    /**
     * @var IOInterface
     **/
    private $io;

    /**
     * Activate this plugin by storing the bin_dir.
     *
     * @see \Composer\Plugin\PluginInterface::activate()
     * @param Composer    $composer the composer instance to pull the config of the bin-dir from.
     * @param IOInterface $io the io interface to write / read output input.
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->bin_dir = $composer->getConfig()->get('bin-dir');
        $this->io      = $io;
    }

    /**
     * Ugly Composer static call to hook in to the post-autoload-dump event.
     *
     * @return array holding the post-autoload-dump hook and function to execute.
     */
    public static function getSubscribedEvents()
    {
        return [ 'post-autoload-dump' => 'execute' ];
    }

    /**
     * This function call is called from the phpcs main composer.json file as an 'pre-dependencies-solving' script.
     * This function makes sure the Download manager ALWAYS uses source instead of dist to ensure the 'test'-folder of
     * PHP Code Sniffer is there...
     *
     * @param InstallerEvent $event the event fired.
     */
    public static function ensureSource(InstallerEvent $event)
    {
        $download_manager = $event->getComposer()->getDownloadManager();
        /*@var $dm \Composer\Downloader\DownloadManager */
        $download_manager->setPreferDist(false);
        $download_manager->setPreferSource(true);
    }

    /**
     * Allow configuration from script, to use and test phpcs standalone
     * @param Event $event the event fired.
     */
    public static function configure(Event $event)
    {
        $root = $event->getComposer()->getPackage()->getName() === 'hostnet/phpcs-tool';
        self::defaultConfig($event->getComposer()->getConfig()->get('bin-dir'), $event->getIo(), $root);
    }

    /**
     * Use executable of phpcs to configure prefereces
     * @param string $setting name of the setting
     * @param string $value value of the setting
     * @param string $bin_dir location of the phpcs executable
     * @param IOInterface $io composer io interface for verbose output
     */
    public static function phpcsConfig($setting, $value, $bin_dir, IOInterface $io)
    {

        $phpcs   = escapeshellarg($bin_dir . '/phpcs');
        $setting = escapeshellarg($setting);
        $value   = escapeshellarg($value);
        $output  = `2>&1 $phpcs --config-set $setting $value`;

        if ($io->isVerbose()) {
            $io->write($output);
        }
    }

    /**
     * Set the default config for phpcs to our desired settings
     * @param string $bin_dir
     * @param IOInterface $io
     */
    public static function defaultConfig($bin_dir, IOInterface $io, $is_root)
    {
        self::phpcsConfig('default_standard', 'Hostnet', $bin_dir, $io);
        self::phpcsConfig('colors', 1, $bin_dir, $io);
        if ($is_root) {
            copy(__DIR__  . '/../../ruleset.xml', __DIR__ . '/../../../../A/B/C/D/Hostnet/ruleset.xml');
            self::phpcsConfig('installed_paths', realpath(__DIR__ . '/../../../../A/B/C/D'), $bin_dir, $io);
        } else {
            self::phpcsConfig('installed_paths', realpath(__DIR__ . '/../../..'), $bin_dir, $io);
        }
    }

    /**
     * The 'logical'-operation of this Installer.
     * PHPCS does not define constants for the config options,
     * doing so ourself would only lead to outdated intel.
     *
     * @see https://github.com/squizlabs/PHP_CodeSniffer/wiki/Configuration-Options
     */
    public function execute()
    {
        self::defaultConfig($this->bin_dir, $this->io, false);
    }
}
