<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Viduc\Metromposer\Configuration\Configuration;
use Viduc\Metromposer\Configuration\ConfigurationInterface;
use Viduc\Metromposer\Exception\MetromposerException;

define('FICHIER', '../config.json');

class ConfigurationTest extends TestCase
{
    protected ConfigurationInterface $configuration;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->configuration = new Configuration(FICHIER);
        $this->supprimerFichier();
    }

    final public function testCreerLeFichier() : void
    {
        self::assertFileDoesNotExist(FICHIER);
        $this->configuration->creerLeFichier();
        self::assertFileExists(FICHIER);
    }

    final public function testAjouterOuModifierUnParametre() : void
    {
        self::assertNull($this->configuration->ajouterOuModifierUnParametre(
            'version',
            '0.0.2'
        ));
        $this->supprimerFichier();
        $this->creerFichier(true);
        try {
            $this->configuration->ajouterOuModifierUnParametre(
                'version',
                '0.0.2'
            );
        } catch (MetromposerException $ex) {
            self::assertEquals(
                'Erreur JSON lors de la lecture ou l\' écriture du fichier',
                $ex->getMessage()
            );
        }
    }

    final public function testRecupereUnParametre() : void
    {
        $this->creerFichier();
        self::assertEquals(
            '0.0.1',
            $this->configuration->recupereUnParametre('version')
        );
        try {
            $this->configuration->recupereUnParametre('test');
        } catch (MetromposerException $ex) {
            self::assertEquals(
                'Le paramètre test n\' existe pas',
                $ex->getMessage()
            );
        }
        $this->supprimerFichier();
        $this->creerFichier(true);
        try {
            $this->configuration->recupereUnParametre('test');
        } catch (MetromposerException $ex) {
            self::assertEquals(
                'Erreur JSON lors de la lecture du fichier',
                $ex->getMessage()
            );
        }
    }

    final public function testRecupererPathApplication() : void
    {
        self::assertIsString(
            $this->configuration->recupererPathApplication()
        );
    }

    final public function testRecupererPathLibrairie() : void
    {
        self::assertIsString(
            $this->configuration->recupererPathLibrairie()
        );
    }

    final public function testRecupererLaVersionDeLaLibrairie() : void
    {
        self::assertIsString(
            $this->configuration->recupererLaVersionDeLaLibrairie()
        );
        try {
            $this->configuration->recupererLaVersionDeLaLibrairie();
        } catch (MetromposerException $ex) {
            self::assertEquals(
                'Erreur JSON lors de la lecture du fichier composer.json',
                $ex->getMessage()
            );
        }
    }

    final public function testEnregistrerLeNomDuServeur() : void
    {
        self::assertNull($this->configuration->enregistrerLeNomDuServeur());
    }

    final public function testDateDinstallation() : void
    {
        self::assertNull($this->configuration->dateDinstallation());
    }

    final public function testRemplacerCaracteresSpeciaux() : void
    {
        self::assertEquals(
            'Mchaine',
            $this->configuration->remplacerCaracteresSpeciaux('M@cha\'îne')
        );
    }

//-------------------------> METHODE INTERNES <------------------------------//
    final private function supprimerFichier() :void
    {
        if (file_exists(FICHIER)) {
            unlink(FICHIER);
        }
    }

    final private function creerFichier(bool $vide = false) : void
    {
        if (!$vide) {
            file_put_contents(
                FICHIER,
                json_encode(['version' => '0.0.1'], JSON_THROW_ON_ERROR)
            );
        } else {
            fopen(FICHIER, 'ab');
        }
    }
}
