<?php
declare(strict_types=1);

namespace Viduc\Metromposer\Installation;

require_once('MessageInterface.php');

use Viduc\Metromposer\Exception\MetromposerException;
use Viduc\Metromposer\Composer\Composer;

define('COULEUR_QUESTION', "\e[96m");
define('FIN_COULEUR_QUESTION', "\e[0m \n\e[91m-->\e[0m ");
define('COULEUR_ERREUR', "\e[31m");
define('FIN_COULEUR_ERREUR', "\e[0m");

class Message implements MessageInterface
{
    private string $entete = "\e[35m " .
    "-------------------------------------------------------------------------" .
    "\e[0m \n \e[35m" .
    "------------------------------ METROMPOSER ------------------------------" .
    "\e[0m \n \e[35m" .
    "------------ Auteur: viduc@mail.fr - https://github.com/viduc -----------" .
    "\e[0m \n \e[35m" .
    "------------    version: 0.1 - 2021 - license: Apache-2.0    ------------" .
    "\e[0m \n \e[35m" .
    "-------------------------------------------------------------------------" .
    "\e[0m \n \n \e[93m" .
    "Installation de la librairie metromposer \e[0m \n\n";

    private $questions = [];

    final public function __construct()
    {
        $this->questions[0] = "Composer est il installé sur votre serveur et " .
        "accessible depuis la commande 'composer' ? (Oui/non) | :q pour annuler";
        $this->questions[1] = "Renseignez l'adresse de votre dépôt git " .
            "(par ex: ssh://login@serveugit.fr/depot.git) ou :q pour quitter";
        $this->questions[2] = "Votre dépôt git n'est pas accessible, renseignez" .
            " une adresse valide (par ex: ssh://login@serveugit.fr/depot.git)" .
            " ou entrez :q pour quitter";
        $composer = new Composer();
        $this->questions[3] = "Quelle version du fichier composer.phar " .
            "souhaitez vous utiliser ? Entrez l'id correspondant à la version" .
            " désirée : (:q pour quitter)\n | ID  | VERSION |\n";
        foreach ($composer->recupererLesVersions() as $key => $value) {
            $this->questions[3] .= " | [" . $key . "] | " . $value;
            $valueLength = strlen($value);
            for ($i=0; $i <=(7-$valueLength); $i++) {
                $this->questions[3] .= ' ';
            }
            $this->questions[3] .="|\n";
        }
    }

    /**
     * Retourne l'en-tête affiché en début de script
     * @return string
     * @test testGetEntete()
     */
    final public function getEntete() : string
    {
        return $this->entete;
    }

    /**
     * Retourne le tableau de questions
     * @return array
     * @test testGetQuestions()
     */
    final public function getQuestions() : array
    {
        return $this->questions;
    }

    /**
     * Récupère une question par son if
     * @param int $id - id de la question
     * @return string
     * @throws MetromposerException
     * @test testGetQuestion()
     */
    final public function getQuestion(int $id): string
    {
        if (array_key_exists($id, $this->questions)) {
            $question = COULEUR_QUESTION;
            $question .= $this->questions[$id];
            $question .= FIN_COULEUR_QUESTION;
            return  $question;
        }
        throw new MetromposerException("L'id " . $id . " n'existe pas");
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    final public function getReponse() : string
    {
        $handle = fopen ("php://stdin", 'rb');
        $reponse = trim(fgets($handle));
        if ($reponse === ':q') {
            exit();
        }
        return $reponse;
    }

    final public function afficherErreur(string $message): void
    {
        echo COULEUR_ERREUR . '\n' . $message . FIN_COULEUR_ERREUR;
        exit();
    }
}
