<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Installation;

use Viduc\Metromposer\Exception\MetromposerException;

/**
 * Interface InstallationInterface
 * @package Viduc\Metromposer\Installation
 */
interface InstallationInterface
{
    /**
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function installer() : void;

    /**
     * vérifie si une installation a déjà été faite
     * @return bool
     * @test testVerifierInstallation()
     * @codeCoverageIgnore
     */
    function verifierInstallation() : bool;

    /**
     * Relance l'installation en supprimant le dossier metromposer
     * @throws MetromposerException
     * @test testRelancerInstallation()
     * @codeCoverageIgnore
     */
    function relancerInstallation() : void;

    /**
     * Récupère l'adresse du dépot git et clone le dépôt
     * @param bool error - détermine si on a eu une erreur lors de la
     * récupération du dépôt. Si vraie message git_error
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function depotGit(bool $error) : void;

    /**
     * Enregsitre la version du fichier phar à utiliser
     * @throws MetromposerException
     * @test testComposerPhar()
     * @codeCoverageIgnore
     */
    function composerPhar() : void;

    /**
     * Enregistre le nom de l'application
     * @throws MetromposerException
     * @test testNomDeLapplication()
     * @codeCoverageIgnore
     */
    function nomDeLapplication() : void;

    /**
     * Enregistre l'url de l'application
     * @throws MetromposerException
     * @test testLienDeLapplication()
     * @codeCoverageIgnore
     */
    function lienDeLapplication() : void;

    /**
     * Génère le rapport html
     * @throws MetromposerException
     * @test testGenererLeRapport()
     * @codeCoverageIgnore
     */
    function genererLeRapport() : void;

    /**
     * Envoie le rapport sur le dépôt git
     * @throws MetromposerException
     * @test testEnvoyerLeRapport();
     * @codeCoverageIgnore
     */
    function envoyerLeRapport() : void;

    /**
     * FIn de l'installation
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function fin() : void;
}