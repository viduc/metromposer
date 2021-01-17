<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Git;

use Viduc\Metromposer\Exception\MetromposerException;

/**
 * Interface GitInterface
 * @package Viduc\Metromposer\Git
 */
interface GitInterface
{
    /**
     * Setter pour la variable urlServeur
     * @param string $urlServeur
     * @codeCoverageIgnore
     */
    function setUrlServeur(string $urlServeur) : void;

    /**
     * Vérifie si le dépot git est accessible
     * @return bool
     * @test testVerifierSiLeDepotEstAccessible()
     * @codeCoverageIgnore
     */
    function verifierSiLeDepotEstAccessible() : bool;

    /**
     * Clone le dépot
     * @return bool
     * @test testClonerLeDepot()
     * @codeCoverageIgnore
     */
    function clonerLeDepot() : bool;

    /**
     * Envoie les modifications sur le dépôt git
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function envoyerLeRapport() : void;
}