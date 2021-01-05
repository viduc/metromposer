<?php

namespace Viduc\Metromposer\Configuration;

use JsonException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Viduc\Metromposer\Exception\MetromposerException;

require_once('ConfigurationInterface.php');

class Configuration implements ConfigurationInterface
{
    private string $fichier;

    final public function __construct(string $fichier = null)
    {
        $this->fichier = $this->recupererPathApplication()
            . 'metromposer/config.json';
        if ($fichier) {
            $this->fichier = $fichier;
        }
    }

    final public function creerLeFichier() : void
    {
        if (!file_exists($this->fichier)) {
            try {
                file_put_contents(
                    $this->fichier,
                    json_encode(
                        [
                            'version' => $this->recupererLaVersionDeLaLibrairie(),
                            'path' => $this->recupererPathApplication()
                        ],
                        JSON_THROW_ON_ERROR
                    )
                );
            } catch (JsonException $e) {
                throw new MetromposerException($e->getMessage());
            }
        }
    }

    /**
     * Ajout ou modifie un paramètre existant dans le fichier config
     * @param string $parametre - le nom du paramètre
     * @param string $value - la valeur du paramètre
     * @return bool
     * @throws MetromposerException
     * @test testAjouterOuModifierUnParametre()
     */
    final public function ajouterOuModifierUnParametre(
        string $parametre,
        string $value
    ): bool {
        $this->creerLeFichier();
        try {
            $config = json_decode(
                file_get_contents($this->fichier),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
            $config->$parametre = $value;
            return file_put_contents(
                $this->fichier,
                json_encode($config, JSON_THROW_ON_ERROR)
            );
        } catch (JsonException $e) {
            throw new MetromposerException(
                'Erreur JSON lors de la lecture ou l\' écriture du fichier'
            );
        }
    }

    /**
     * Récupère un paramètre depuis le fichier de config
     * @param string $parametre - le nom du paramètre
     * @return string - la valeur du paramètre
     * @throws MetromposerException
     * @test testRecupereUnParametre()
     */
    final public function recupereUnParametre(string $parametre): string
    {
        try {
            $config = json_decode(
                file_get_contents($this->fichier),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
            if (isset($config->$parametre)) {
                return $config->$parametre;
            }

            throw new MetromposerException(
                'Le paramètre ' . $parametre . ' n\' existe pas'
            );

        } catch (JsonException $e) {
            throw new MetromposerException(
                'Erreur JSON lors de la lecture du fichier'
            );
        }
    }

    /**
     * Récupère le path complet de l'application
     * @return string
     * @test testRecupererPathApplication()
     */
    final public function recupererPathApplication() : string
    {
        $output=null;
        $retval=null;
        $commande = 'pwd 2> /dev/null';
        exec($commande, $output, $retval);
        $vendor = substr(
            $output[0],
            0,
            strpos($output[0], 'Viduc/Metromposer')
        );

        return str_replace(['vendor/', 'librairie/'], '', $vendor);
    }

    /**
     * Récupère le path complet de la librairie
     * @return string
     * @test testRecupererPathLibrairie()
     */
    final public function recupererPathLibrairie() : string
    {
        $output=null;
        $retval=null;
        $commande = 'pwd 2> /dev/null';
        exec($commande, $output, $retval);
        return substr(
            $output[0],
            0,
            strpos($output[0], 'Viduc/Metromposer')
        ) . 'Viduc/Metromposer';

    }

    /**
     * récupère la version de la librairie
     * @return string
     * @throws MetromposerException
     * @test testRecupererLaVersionDeLaLibrairie()
     */
    final public function recupererLaVersionDeLaLibrairie() : string
    {
        try {
            $json = json_decode(
                file_get_contents(
                    $this->recupererPathLibrairie() . '/composer.json'
                ),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
            if (isset($json->version)) {
                return $json->version;
            }

            throw new MetromposerException(
                'La version de la librairie n\' existe pas'
            );

        } catch (JsonException $e) {
            throw new MetromposerException(
                'Erreur JSON lors de la lecture du fichier composer.json'
            );
        }
    }

    /**
     * Enregistre le nom du serveur
     * @throws MetromposerException
     * @test testEnregistrerLeNomDuServeur()
     */
    final public function enregistrerLeNomDuServeur() : void
    {
        $this->ajouterOuModifierUnParametre('serveur', gethostname());
    }

    /**
     * Enregistre la date de l'installation
     * @throws MetromposerException
     * @test testDateDinstallation()
     */
    final public function dateDinstallation() : void
    {
        $this->ajouterOuModifierUnParametre(
            'dateInstallation',
            date("d-m-Y H:i:s")
        );
    }

    /**
     * Supprime un dossier et son contenu
     * @param string $path
     * @codeCoverageIgnore
     */
    final public function supprimerDossier(string $path) : void
    {
        $it = new RecursiveDirectoryIterator(
            $path,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $files = new RecursiveIteratorIterator(
            $it,
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }

    /**
     * Remplace les caractères spéciaux d'une chaine
     * @param string $chaine
     * @return string
     * @test testRemplacerCaracteresSpeciaux()
     */
    final public function remplacerCaracteresSpeciaux(string $chaine) : string
    {
        setlocale(LC_ALL, 'fr_FR');
        $chaine = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $chaine);
        $chaine = preg_replace('#[^0-9a-z]+#i', '', $chaine);
        while(strpos($chaine, '--') !== false)
        {
            $chaine = str_replace('--', '-', $chaine);
        }
        $chaine = trim($chaine, '');

        return $chaine;
    }
}