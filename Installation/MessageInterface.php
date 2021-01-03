<?php
namespace Viduc\Metromposer\Installation;

use Viduc\Metromposer\Exception\MetromposerException;

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

    function afficherErreur(string $message) : void;

}