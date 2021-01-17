<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace Viduc\Metromposer\Installation;

use Viduc\Metromposer\Exception\MetromposerException;

/**
 * Interface MessageInterface
 * @package Viduc\Metromposer\Installation
 * @codeCoverageIgnore
 */
interface MessageInterface
{
    /**
     * Retourne l'en-tête affiché en début de script
     * @return string
     * @test testGetEntete()
     */
    function getEntete() : string;

    /**
     * Retourne le tableau de questions
     * @return array
     * @test testGetQuestions()
     */
    function getQuestions() : array;

    /**
     * Récupère une question par son if
     * @param string $name - le nom du message
     * @return string
     * @throws MetromposerException
     * @test testGetQuestion()
     */
    function getQuestion(string $name): string;

    /**
     * @return string
     * @codeCoverageIgnore
     */
    function getReponse() : string;

    /**
     * Affiche le message d'erreur
     * @param string $message
     * @codeCoverageIgnore
     */
    function afficherErreur(string $message) : void;
}