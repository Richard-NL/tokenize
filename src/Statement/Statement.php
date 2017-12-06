<?php declare(strict_types=1);
namespace App\Statement;


abstract class Statement
{

    private $subject;

    /**
     * @var string
     */
    private $withClause;

    public function __construct(string $subject, string $withClause = null)
    {
        $this->subject = $subject;
        $this->withClause = $withClause;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getWithClause(): ?string
    {
        return $this->withClause;
    }
}
