<?php declare(strict_types=1);

namespace App\Parser;

class TextCommandParser
{
    /** @var Lexer  */
    private $lexer;

    public function __construct(string $textCommand)
    {
        $this->lexer = new Lexer();
        $this->lexer->setInput($textCommand);
    }

    public function getAST()
    {
        $AST = $this->TextCommandLanguage();
        return $AST;
    }

    public function TextCommandLanguage()
    {
        $this->lexer->moveNext();

        $statement = $this->determineStatementClass();

        if ($statement === \App\Statement\InvalidStatement::class) {
            throw new \RuntimeException('invalid command');
        }

        $value = null;

        $ignorableWords = ['the', 'an', 'a', 'to', 'at'];
        while ($this->lexer->isNextToken(Lexer::T_ACTION_SUBJECT) === false || in_array($this->lexer->lookahead['value'], $ignorableWords)) {
            $this->lexer->moveNext();
        }

        $value = $this->lexer->lookahead['value'];



        return new $statement($value);
    }

    /**
     * @return string
     */
    private function determineStatementClass()
    {
        $statement = null;

        switch ($this->lexer->lookahead['type']) {
            case Lexer::T_GOTO_ACTION:
                $statement = \App\Statement\GotoStatement::class;
                break;
            case Lexer::T_LOOKAT_ACTION:
                $statement = \App\Statement\LookAtStatement::class;
                break;
            case Lexer::T_PICKUP_ACTION:
                $statement = \App\Statement\PickUpStatement::class;
                break;
            case Lexer::T_OPEN_ACTION:
                $statement = \App\Statement\OpenStatement::class;
                break;
            default:
                $statement = \App\Statement\InvalidStatement::class;
        }
        return $statement;
    }
}
