<?php
namespace Hostnet\Component\CodeSniffer;
use Composer\Plugin\PluginEvents;

use Composer\EventDispatcher\EventSubscriberInterface;

use Composer\IO\IOInterface;
use Composer\Composer;
use Composer\Plugin\PluginInterface;

class Installer implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        // TODO: Auto-generated method stub
        echo "Yo Ho!\n";
    }
    /*
     *             "echo 'Swapping out phpcs'",
            "rm -Rf vendor/bin/phpcs",
            "rm -Rf vendor/bin/phpcbf",
            "ln -s vendor/bin/hn_phpcs vendor/bin/phpcs",
            "ln -s vendor/bin/hn_phpcbf vendor/bin/phpcbf"
     */
    public function getSubscribedEvents()
    {
        return array(
            'post-autoload-dump' => array(
                array('execute', 0)
            ),
        );

    }

    public function execute(){
        echo "Here we go, again!!\n";
    }

}
