<?php
namespace Viduc\Metromposer\Installation;

use Viduc\Metromposer\Configuration\Configuration;
use Viduc\Metromposer\Configuration\ConfigurationInterface;
use Viduc\Metromposer\Exception\MetromposerException;
use Viduc\Metromposer\Git\GitInterface;
use Viduc\Metromposer\Git\Git;
use Viduc\Metromposer\Composer\ComposerInterface;
use Viduc\Metromposer\Composer\Composer;

class Installation implements InstallationInterface
{
    private MessageInterface $messages;
    private GitInterface $git;
    private ConfigurationInterface $configuration;
    private ComposerInterface $composer;

    final public function setMessage(MessageInterface $message) : void
    {
        $this->messages = $message;
    }

    final public function setGit(GitInterface $git) : void
    {
        $this->git = $git;
    }

    final public function setConfiguration(ConfigurationInterface $config) : void
    {
        $this->configuration = $config;
    }

    final public function setComposer(ComposerInterface $composer) : void
    {
        $this->composer = $composer;
    }

    final public function __construct()
    {
        $this->messages = new Message();
        $this->git = new Git();
        $this->configuration = new Configuration();
        $this->composer = new Composer();
    }

    /**
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    final public function installer() : void
    {
        echo $this->messages->getEntete();

        try {
            $this->depotGit();
            if (!$this->composerServeur()) {
                $this->composerPhar();
            }
        } catch (MetromposerException $ex) {
            $this->messages->afficherErreur($ex->getMessage());
        }
    }

    /**
     * Récupère l'adresse du dépot git et clone le dépôt
     * @param int $id - l'id du message (pour la boucle)
     * @throws MetromposerException
     */
    final public function depotGit(int $id = 1) : void
    {
        echo $this->messages->getQuestion($id);
        $serveur = $this->messages->getReponse();
        $this->git->setUrlServeur($serveur);
        if (!$this->git->verifierSiLeDepotEstAccessible()) {
            $this->depotGit(2);
        }
        $this->git->clonerLeDepot();
        $this->configuration->ajouterOuModifierUnParametre('git', $serveur);
    }

    /**
     * Validation de l'installation du composer en mode serveur
     * @return bool
     * @throws MetromposerException
     */
    final public function composerServeur(): bool
    {
        echo $this->messages->getQuestion(0);
        $reponse = $this->messages->getReponse();
        if ($reponse !== 'oui' && $reponse !== 'non') {
            return $this->composerServeur();
        }
        if ($reponse === 'oui') {
            $this->configuration->ajouterOuModifierUnParametre(
                'composer',
                'serveur'
            );
             return true;
        }

        return false;
    }

    /**
     * Enregsitre la version du fichier phar à utiliser
     * @throws MetromposerException
     * @test testComposerPhar()
     */
    final public function composerPhar() : void
    {
        echo $this->messages->getQuestion(3);
        $versions = $this->composer->recupererLesVersions();
        $reponse = $this->messages->getReponse();
        if (array_key_exists(
            $reponse,
            $versions
        )) {
            $this->configuration->ajouterOuModifierUnParametre(
                'composer',
                $versions[$reponse]
            );
        } else {
            $this->composerPhar();
        }
    }
}