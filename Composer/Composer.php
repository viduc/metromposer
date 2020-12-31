<?php declare(strict_types=1);

namespace Viduc\Metromposer\Composer;

use Viduc\Metromposer\Configuration\Configuration;

require_once('ComposerInterface.php');

class Composer implements ComposerInterface
{
    private Configuration $configuration;

    public function __construct()
    {
        $this->configuration = new Configuration();
    }
    /**
     * Récupère les versions des fichiers phar disponibles
     * @return array
     * @test testRecupererLesVersions()
     */
    final public function recupererLesVersions() : array
    {
        $versions = [];
        $files = scandir(
            $this->configuration->recupererPathLibrairie()
            . '/Composer'
        );
        foreach ($files as $file) {
            if (strpos($file, 'composer-') === 0) {
                $version = str_replace(
                    ['composer-', '.phar'],
                    '',
                    $file
                );
                $versions[] = $version;
            }
        }

        return $versions;
    }

}