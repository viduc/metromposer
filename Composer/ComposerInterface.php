<?php
namespace Viduc\Metromposer\Composer;

interface ComposerInterface
{
    /**
     * Récupère les versions des fichiers phar disponibles
     * @return array
     * @test testRecupererLesVersions()
     */
    function recupererLesVersions() : array;
}