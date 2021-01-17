<?php
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Composer;

use Viduc\Metromposer\Exception\MetromposerException;

/**
 * Interface ComposerInterface
 * @package Viduc\Metromposer\Composer
 */
interface ComposerInterface
{
    /**
     * Récupère les versions des fichiers phar disponibles
     * @return array
     * @test testRecupererLesVersions()
     * @codeCoverageIgnore
     */
    function recupererLesVersions() : array;

    /**
     * Génère le fichier des librairies à mettre à jour
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function genererLaListeDesLibrairiesAmettreAjour() : void;

    /**
     * Formate une ligne
     * @param string $ligne
     * @return string
     * @test testFormaterLigneComposer()
     * @codeCoverageIgnore
     */
    function formaterLigneComposer(string $ligne) : string;
}