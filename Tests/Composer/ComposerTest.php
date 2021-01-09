<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Tests\Composer;

use PHPUnit\Framework\TestCase;
use Viduc\Metromposer\Composer\Composer;
use Viduc\Metromposer\Configuration\Configuration;
use Viduc\Metromposer\Configuration\ConfigurationInterface;

class ComposerTest extends TestCase
{
    protected $composer;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->composer = new Composer();
    }

    final public function testRecupererLesVersions() : void
    {
        self::assertCount(7, $this->composer->recupererLesVersions());
    }

    final public function testGenererLaListeDesLibrairiesAmettreAjour() : void
    {
        $config = new Configuration();
        if (!is_dir($config->recupererPathApplication() . '/metromposer')) {
            mkdir($config->recupererPathApplication() . '/metromposer');
        }
        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('recupereUnParametre')->willReturn('test');
        $this->composer->setConfiguration($configuration);
        self::assertNull(
            $this->composer->genererLaListeDesLibrairiesAmettreAjour()
        );
    }

    final public function testFormaterLigneComposer() : void
    {
        $chaine = 'machaine/machaine test   test test';
        $attendu = '<tr><td>MAJ</td><td>machaine/machaine</td><td style="width:'
            . ' 10%">test -> test</td><td></td></tr>';
        self::assertEquals(
            $attendu,
            $this->composer->formaterLigneComposer($chaine)
        );
    }
}
