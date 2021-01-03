<?php
namespace Viduc\Metromposer\Composer;

use Viduc\Metromposer\Exception\MetromposerException;

interface ComposerInterface
{
    /**
     * Récupère les versions des fichiers phar disponibles
     * @return array
     * @test testRecupererLesVersions()
     */
    function recupererLesVersions() : array;

    /**
     * Génère le fichier des librairies à mettre à jour
     * @throws MetromposerException
     */
    function genererLaListeDesLibrairiesAmettreAjour() : void;
}