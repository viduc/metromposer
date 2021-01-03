<?php declare(strict_types=1);
namespace Viduc\Metromposer\Tests\Installation;

use Viduc\Metromposer\Composer\ComposerInterface;
use Viduc\Metromposer\Configuration\ConfigurationInterface;
use Viduc\Metromposer\Git\GitInterface;
use Viduc\Metromposer\Installation\Installation;
use Viduc\Metromposer\Installation\MessageInterface;

use PHPUnit\Framework\TestCase;

class InstallationTest extends TestCase
{
    protected Installation $installation;
    protected MessageInterface $message;
    protected GitInterface $git;
    protected ConfigurationInterface $configuration;
    protected ComposerInterface  $composer;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->installation = new Installation();
        $this->message = $this->createMock(MessageInterface::class);
        $this->git = $this->createMock(GitInterface::class);
        $this->configuration = $this->createMock(ConfigurationInterface::class);
        $this->composer = $this->createMock(ComposerInterface::class);

        $this->installation->setMessage($this->message);
        $this->installation->setGit($this->git);
        $this->installation->setConfiguration($this->configuration);
        $this->installation->setComposer($this->composer);
    }

    final public function testVerifierInstallation() : void
    {
        self::assertIsBool($this->installation->verifierInstallation());
    }

    final public function testRelancerInstallation() : void
    {
        $this->message->method('getQuestion')->will(
            self::onConsecutiveCalls('test', 'test')
        );
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test', 'oui')
        );
        $this->configuration->method('supprimerDossier')->will(
            self::onConsecutiveCalls(true)
        );
        $this->configuration->method('recupererPathApplication')->will(
            self::onConsecutiveCalls('test', 'test')
        );

        self::assertNull($this->installation->relancerInstallation());
        //self::assertNull($this->installation->relancerInstallation());
    }

    final public function testDepotGit() : void
    {
        $this->message->method('getQuestion')->will(
            self::onConsecutiveCalls('test1', 'test2')
        );
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test1', 'test2')
        );
        $this->git->method('verifierSiLeDepotEstAccessible')->will(
            self::onConsecutiveCalls(false, true)
        );
        $this->git->method('setUrlServeur')->will(
            self::onConsecutiveCalls(true, true)
        );
        $this->configuration->method('ajouterOuModifierUnParametre')->will(
            self::onConsecutiveCalls(true, true)
        );
        self::assertNull($this->installation->depotGit());
    }

    final public function testComposerPhar() : void
    {
        $this->composer->method('recupererLesVersions')->will(
            self::onConsecutiveCalls([], [0 => 'test'])
        );
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('10', '0')
        );
        $this->message->method('getQuestion')->will(
            self::onConsecutiveCalls('test', 'test')
        );
        $this->configuration->method('ajouterOuModifierUnParametre')->will(
            self::onConsecutiveCalls(true)
        );
        self::assertNull($this->installation->composerPhar());
    }

    final public function testNomDeLapplication() : void
    {

        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test')
        );
        $this->configuration->method('ajouterOuModifierUnParametre')->will(
            self::onConsecutiveCalls(true)
        );
        self::assertNull($this->installation->nomDeLapplication());
    }

    final public function testLienDeLapplication() : void
    {
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test')
        );
        $this->configuration->method('ajouterOuModifierUnParametre')->will(
            self::onConsecutiveCalls(true)
        );
        self::assertNull($this->installation->lienDeLapplication());
    }

    final public function testGenererLeRapport() : void
    {
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test', 'oui')
        );
        $this->composer->method('genererLaListeDesLibrairiesAmettreAjour')->will(
            self::onConsecutiveCalls('test')
        );
        self::assertNull(
            $this->installation->genererLeRapport()
        );
    }

    final public function testEnvoyerLeRapport() : void
    {
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('test', 'oui')
        );
        $this->git->method('envoyerLeRapport')->will(
            self::onConsecutiveCalls(true)
        );
        self::assertNull(
            $this->installation->envoyerLeRapport()
        );
    }

}
