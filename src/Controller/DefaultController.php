<?php declare(strict_types=1);
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    /**
     * @Route("/tokenize")
     * @return Response
     */
    public function tokenize()
    {
        $parser = new \App\Parser\TextCommandParser('get stone');
        $AST = $parser->getAST();
        var_dump($AST);
        return new Response('a');
    }
}
