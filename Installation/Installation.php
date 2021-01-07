<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
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

    /**
     * @param MessageInterface $message
     * @codeCoverageIgnore
     */
    final public function setMessage(MessageInterface $message) : void
    {
        $this->messages = $message;
    }

    /**
     * @param GitInterface $git
     * @codeCoverageIgnore
     */
    final public function setGit(GitInterface $git) : void
    {
        $this->git = $git;
    }

    /**
     * @param ConfigurationInterface $config
     * @codeCoverageIgnore
     */
    final public function setConfiguration(ConfigurationInterface $config) : void
    {
        $this->configuration = $config;
    }

    /**
     * @param ComposerInterface $composer
     * @codeCoverageIgnore
     */
    final public function setComposer(ComposerInterface $composer) : void
    {
        $this->composer = $composer;
    }

    /**
     * Installation constructor.
     * @codeCoverageIgnore
     */
    final public function __construct()
    {
        $this->messages = new Message();
        $this->git = new Git();
        $this->configuration = new Configuration();
        $this->composer = new Composer();
    }

    /**
     * @codeCoverageIgnore
     * @throws MetromposerException
     */
    final public function installer() : void
    {
        echo $this->messages->getEntete();
        if ($this->verifierInstallation()) {
            $this->relancerInstallation();
        }
        try {
            $this->depotGit();
            $this->composerPhar();
            $this->nomDeLapplication();
            $this->lienDeLapplication();
            $this->configuration->enregistrerLeNomDuServeur();
            $this->configuration->dateDinstallation();
            $this->genererLeRapport();
            $this->envoyerLeRapport();
            $this->fin();
        } catch (MetromposerException $ex) {
            $this->messages->afficherErreur($ex->getMessage());
        }
    }

    /**
     * vérifie si une installation a déjà été faite
     * @return bool
     * @test testVerifierInstallation()
     */
    final public function verifierInstallation() : bool
    {
        return is_dir(
            $this->configuration->recupererPathApplication()
            . '/metromposer'
        );
    }

    /**
     * Relance l'installation en supprimant le dossier metromposer
     * @throws MetromposerException
     * @test testRelancerInstallation()
     */
    final public function relancerInstallation() : void
    {
        echo $this->messages->getQuestion('relancerInstallation');
        $reponse = $this->messages->getReponse();
        if ($reponse !== 'oui' && $reponse !== 'non') {
            $this->relancerInstallation();
        }
        // @codeCoverageIgnoreStart
        if ($reponse === 'non') {
            exit();
        }
        // @codeCoverageIgnoreEnd
        $this->configuration->supprimerDossier(
            $this->configuration->recupererPathApplication(). '/metromposer'
        );
    }

    /**
     * Récupère l'adresse du dépot git et clone le dépôt
     * @param bool error - détermine si on a eu une erreur lors de la
     * récupération du dépôt. Si vraie message git_error
     * @throws MetromposerException
     * @test testDepotGit()
     */
    final public function depotGit(bool $error = false) : void
    {
        $message =  $error ? 'git_error' : 'git';
        echo $this->messages->getQuestion($message);
        $serveur = $this->messages->getReponse();
        $this->git->setUrlServeur($serveur);
        if (!$this->git->verifierSiLeDepotEstAccessible()) {
            $this->depotGit(true);
        }
        $this->git->clonerLeDepot();
        $this->configuration->ajouterOuModifierUnParametre('git', $serveur);
    }

    /**
     * Enregsitre la version du fichier phar à utiliser
     * @throws MetromposerException
     * @test testComposerPhar()
     */
    final public function composerPhar() : void
    {
        echo $this->messages->getQuestion('composer');
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

    /**
     * Enregistre le nom de l'application
     * @throws MetromposerException
     * @test testNomDeLapplication()
     */
    final public function nomDeLapplication() : void
    {
        echo $this->messages->getQuestion('application');
        $this->configuration->ajouterOuModifierUnParametre(
            'application',
            $this->configuration->remplacerCaracteresSpeciaux(
                $this->messages->getReponse()
            )
        );
    }

    /**
     * Enregistre l'url de l'application
     * @throws MetromposerException
     * @test testLienDeLapplication()
     */
    final public function lienDeLapplication() : void
    {
        echo $this->messages->getQuestion('url');
        $this->configuration->ajouterOuModifierUnParametre(
            'url',
            $this->messages->getReponse()
        );
    }

    /**
     * Génère le rapport html
     * @throws MetromposerException
     * @test testGenererLeRapport()
     */
    final public function genererLeRapport() : void
    {
        echo $this->messages->getQuestion('rapport');
        $reponse = $this->messages->getReponse();
        if ($reponse !== 'oui') {
            $this->genererLeRapport();
        }
        if ($reponse === 'oui') {
            $this->composer->genererLaListeDesLibrairiesAmettreAjour();
        }
    }

    /**
     * Envoie le rapport sur le dépôt git
     * @throws MetromposerException
     * @test testEnvoyerLeRapport();
     */
    final public function envoyerLeRapport() : void
    {
        echo $this->messages->getQuestion('push');
        $reponse = $this->messages->getReponse();
        if ($reponse !== 'oui') {
            $this->envoyerLeRapport();
        }
        if ($reponse === 'oui') {
            $this->git->envoyerLeRapport();
        }
    }

    /**
     * FIn de l'installation
     * @throws MetromposerException
     * @codeCoverageIgnore
     */
    final public function fin() : void
    {
        echo $this->messages->getQuestion('fin');
        exit();
    }
}