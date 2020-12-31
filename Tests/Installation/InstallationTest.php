<?php declare(strict_types=1);
namespace Viduc\Metromposer\Tests\Installation;

use phpDocumentor\Reflection\Types\Void_;
use Viduc\Metromposer\Composer\ComposerInterface;
use Viduc\Metromposer\Configuration\ConfigurationInterface;
use Viduc\Metromposer\Git\GitInterface;
use Viduc\Metromposer\Installation\Installation;
use Viduc\Metromposer\Exception\MetromposerException;
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
        $this->installation->setMessage($this->message);
        $this->installation->setGit($this->git);
        $this->installation->setConfiguration($this->configuration);
        self::assertNull($this->installation->depotGit());
    }

    final public function testComposerServeur() : void
    {
        $this->message->method('getReponse')->will(
            self::onConsecutiveCalls('toto', 'oui')
        );
        $this->installation->setMessage($this->message);
        $this->configuration->method('ajouterOuModifierUnParametre')->will(
            self::onConsecutiveCalls(true, true)
        );
        $this->installation->setConfiguration($this->configuration);
        self::assertTrue($this->installation->composerServeur());

        $this->message->method('getQuestion')->willThrowException(
            new MetromposerException('test')
        );
        $this->installation->setMessage($this->message);
        try {
            $this->installation->composerServeur();
        } catch (MetromposerException $ex) {
            self::assertEquals('test', $ex->getMessage());
        }
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
        $this->installation->setConfiguration($this->configuration);
        $this->installation->setMessage($this->message);
        $this->installation->setComposer($this->composer);
        self::assertNull($this->installation->composerPhar());
    }

}
