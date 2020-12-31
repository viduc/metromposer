<?php
namespace Viduc\Metromposer\Configuration;

use Viduc\Metromposer\Exception\MetromposerException;

interface ConfigurationInterface
{
    function creerLeFichier() : void;

    /**
     * Ajout ou modifie un paramètre existant dans le fichier config
     * @param string $parametre - le nom du paramètre
     * @param string $value - la valeur du paramètre
     * @return bool
     * @throws MetromposerException
     * @test testAjouterOuModifierUnParametre()
     */
    function ajouterOuModifierUnParametre(string $parametre, string $value) : bool;

    /**
     * Récupère un paramètre depuis le fichier de config
     * @param string $parametre - le nom du paramètre
     * @return string - la valeur du paramètre
     * @throws MetromposerException
     * @test testRecupereUnParametre()
     */
    function recupereUnParametre(string $parametre) : string;

    /**
     * Récupère le path complet de l'application
     * @return string
     * @test testRecupererPathApplication()
     */
    function recupererPathApplication() : string;

    /**
     * Récupère le path complet de la librairie
     * @return string
     * @test testRecupererPathLibrairie()
     */
    function recupererPathLibrairie() : string;

    function recupererLaVersionDeLaLibrairie() : string;

}