<?php
namespace Viduc\Metromposer\Git;


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

    function envoyerLeRapport() : void;
}