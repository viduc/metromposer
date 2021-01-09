<?php declare(strict_types=1);
namespace Viduc\Metromposer\Tests\Installation;

use PHPUnit\Framework\TestCase;
use Viduc\Metromposer\Exception\MetromposerException;
use Viduc\Metromposer\Installation\Message;

class MessageTest extends TestCase
{
    protected Message $message;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->message = new Message();
    }

    final public function testGetQuestions() :void
    {
        self::assertTrue(count($this->message->getQuestions())>=1);
    }

    final public function testGetEntete() :void
    {
        self::assertIsString($this->message->getEntete());
    }

    final public function testGetQuestion() :void
    {
        self::assertIsString($this->message->getQuestion('git'));
        try {
            $this->message->getQuestion('none');
        } catch(MetromposerException $ex) {
            self::assertEquals("L'id 666 n'existe pas", $ex->getMessage());
        }
    }

}
