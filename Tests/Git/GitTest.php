<?php declare(strict_types=1);
/******************************************************************************/
/*                                  METROMPOSER                               */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/
namespace Viduc\Metromposer\Tests\Git;

use PHPUnit\Framework\TestCase;
use Viduc\Metromposer\Git\Git;

class GitTest extends TestCase
{
    protected $git;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->git = new Git();
    }

    final public function testVerifierSiLeDepotEstAccessible() : void
    {
        $this->git->setUrlServeur('ssh://test@testyouyou.fr/test.git');
        self::assertFalse($this->git->verifierSiLeDepotEstAccessible());
    }

    final public function testClonerLeDepot() : void
    {
        $this->git->setUrlServeur('ssh://test@testyouyou.fr/test.git');
        self::assertFalse($this->git->clonerLeDepot());
    }
}
