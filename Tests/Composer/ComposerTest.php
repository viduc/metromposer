<?php declare(strict_types=1);
namespace Viduc\Metromposer\Tests\Composer;

use PHPUnit\Framework\TestCase;
use Viduc\Metromposer\Composer\Composer;

class ComposerTest extends TestCase
{
    protected Composer $composer;

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
        self::assertNull(
            $this->composer->genererLaListeDesLibrairiesAmettreAjour()
        );
    }

}
