<?php
namespace Hostnet\Component\CodeSniffer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

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
     * Activate this plugin by storing the bin_dir.
     *
     * @see \Composer\Plugin\PluginInterface::activate()
     * @param Composer    $composer the composer instance to pull the config of the bin-dir from.
     * @param IOInterface $io the io interface to write / read output input.
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->bin_dir = $composer->getConfig()->get('bin-dir');
    }

    /**
     * Ugly Composer static call to hook in to the post-autoload-dump event.
     *
     * @return array holding the post-autoload-dump hook and function to execute.
     */
    public static function getSubscribedEvents()
    {
        return [
            'post-autoload-dump' => [
                ['execute', 0]
            ],
        ];
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
     * The 'logical'-operation of this Installer.
     */
    public function execute()
    {
        //replace phpcs
        if (is_file($this->bin_dir . '/phpcs')) {
            unlink($this->bin_dir . '/phpcs');
        }
        symlink($this->bin_dir . '/hn_phpcs', $this->bin_dir . '/phpcs');

        //replace phpcbf
        if (is_file($this->bin_dir . '/phpcbf')) {
            unlink($this->bin_dir . '/phpcbf');
        }
        symlink($this->bin_dir . '/hn_phpcbf', $this->bin_dir . '/phpcbf');
    }
}
