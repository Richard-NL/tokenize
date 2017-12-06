<?php declare(strict_types=1);

namespace App\Parser;

class TextCommandParser
{
    const IGNORABLE_WORDS = ['the', 'an', 'a', 'to', 'at'];

    /** @var Lexer  */
    private $lexer;

    public function __construct(string $textCommand)
    {
        $this->lexer = new Lexer();
        $this->lexer->setInput($textCommand);
//        while ($this->lexer->moveNext()) {
//            var_dump($this->lexer->token);
//        }
//        var_dump($this->lexer->token);
//        die;
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

        $value = $this->determineValue();

        $withValue = $this->determineWithValue();

        return new $statement($value, $withValue);
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

    /**
     * @return string
     */
    private function determineValue(): ?string
    {
        while ($this->lexer->isNextToken(Lexer::T_STRING) === false
            || in_array(
                $this->lexer->lookahead['value'],
                self::IGNORABLE_WORDS
            )
        ) {
            if ($this->lexer->moveNext() === false) {
                break;
            }
        }

       return $this->lexer->lookahead['value'];
    }

    private function isWithConditionPresent(): bool
    {
        $hasWithCondition = false;
        while ($this->lexer->moveNext() === true) {
            if ($this->lexer->isNextToken(Lexer::T_WITH) ) {
                $hasWithCondition = true;
                break;
            }
        }

        return $hasWithCondition;
    }

    private function determineWithValue(): ?string
    {
        $withValue = null;
        if ($this->isWithConditionPresent()) {

            $withValue = $this->determineValue();
        }
        return $withValue;
    }
}
