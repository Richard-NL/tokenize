<?php declare(strict_types=1);

namespace App\Parser;

use App\Statement\GotoStatement;
use App\Statement\OpenStatement;
use App\Statement\PickUpStatement;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class TextCommandParserTest extends TestCase
{
    /**
     * @param string $textCommand
     * @param string $expectedClass
     * @param string $expectedSubject
     * @dataProvider statementProvider
     */
    public function testStatement(string $textCommand, string $expectedClass, string $expectedSubject)
    {
        $textCommandParser = new TextCommandParser($textCommand);

        $statement = $textCommandParser->getAST();
        $this->assertInstanceOf($expectedClass, $statement);
        $this->assertSame($expectedSubject, $statement->getSubject());
    }

    public function statementProvider()
    {
        return [
            ['go to the house', GotoStatement::class, 'house'],
            ['go north', GotoStatement::class, 'north'],
            ['go to the door', GotoStatement::class, 'door'],
            ['pickup rock', PickUpStatement::class, 'rock'],
            ['get the sword', PickUpStatement::class, 'sword'],
        ];
    }

    public function testWithValue()
    {
        $textCommandParser = new TextCommandParser('open door with keycard');
        $statement = $textCommandParser->getAST();
        $this->assertInstanceOf(OpenStatement::class, $statement);
        $this->assertSame('door', $statement->getSubject());
        $this->assertSame('keycard', $statement->getWithValue());

    }
}
