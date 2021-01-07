<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Git;

use Viduc\Metromposer\Exception\MetromposerException;

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
     */
    function verifierSiLeDepotEstAccessible() : bool;

    /**
     * Clone le dépot
     * @return bool
     * @test testClonerLeDepot()
     */
    function clonerLeDepot() : bool;

    /**
     * Envoie les modifications sur le dépôt git
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    function envoyerLeRapport() : void;
}