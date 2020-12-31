<?php
namespace Viduc\Metromposer\Installation;

use Viduc\Metromposer\Exception\MetromposerException;

interface InstallationInterface
{
    /**
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function installer() : void;

    /**
     * Récupère l'adresse du dépot git et clone le dépôt
     * @param int $id - l'id du message (pour la boucle)
     * @throws MetromposerException
     */
    function depotGit(int $id) : void;

    /**
     * Validation de l'installation du composer en mode serveur
     * @return bool
     * @throws MetromposerException
     */
    function composerServeur(): bool;

    /**
     * Enregsitre la version du fichier phar à utiliser
     * @throws MetromposerException
     * @test testComposerPhar()
     */
    function composerPhar() : void;
}