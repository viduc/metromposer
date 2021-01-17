<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Configuration;

use Viduc\Metromposer\Exception\MetromposerException;

/**
 * Interface ConfigurationInterface
 * @package Viduc\Metromposer\Configuration
 */
interface ConfigurationInterface
{
    /**
     * Créer le fichier de configuration
     * @throws MetromposerException
     * @test testCreerLeFichier()
     * @codeCoverageIgnore
     */
    function creerLeFichier() : void;

    /**
     * Ajout ou modifie un paramètre existant dans le fichier config
     * @param string $parametre - le nom du paramètre
     * @param string $value - la valeur du paramètre
     * @return void
     * @throws MetromposerException
     * @test testAjouterOuModifierUnParametre()
     * @codeCoverageIgnore
     */
    function ajouterOuModifierUnParametre(string $parametre, string $value) : void;

    /**
     * Récupère un paramètre depuis le fichier de config
     * @param string $parametre - le nom du paramètre
     * @return string - la valeur du paramètre
     * @throws MetromposerException
     * @test testRecupereUnParametre()
     * @codeCoverageIgnore
     */
    function recupereUnParametre(string $parametre) : string;

    /**
     * Récupère le path complet de l'application
     * @return string
     * @test testRecupererPathApplication()
     * @codeCoverageIgnore
     */
    function recupererPathApplication() : string;

    /**
     * Récupère le path complet de la librairie
     * @return string
     * @test testRecupererPathLibrairie()
     * @codeCoverageIgnore
     */
    function recupererPathLibrairie() : string;

    /**
     * récupère la version de la librairie
     * @return string
     * @throws MetromposerException
     * @test testRecupererLaVersionDeLaLibrairie()
     * @codeCoverageIgnore
     */
    function recupererLaVersionDeLaLibrairie() : string;

    /**
     * Enregistre le nom du serveur
     * @throws MetromposerException
     * @test testEnregistrerLeNomDuServeur()
     * @codeCoverageIgnore
     */
    function enregistrerLeNomDuServeur() : void;

    /**
     * Enregistre la date de l'installation
     * @throws MetromposerException
     * @test testDateDinstallation()
     * @codeCoverageIgnore
     */
    function dateDinstallation() : void;

    /**
     * Supprime un dossier et son contenu
     * @param string $path
     * @codeCoverageIgnore
     */
    function supprimerDossier(string $path) : void;

    /**
     * Remplace les caractères spéciaux d'une chaine
     * @param string $chaine
     * @return string
     * @test testRemplacerCaracteresSpeciaux()
     * @codeCoverageIgnore
     */
    function remplacerCaracteresSpeciaux(string $chaine) : string;

}