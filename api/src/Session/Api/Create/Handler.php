<?php

declare(strict_types=1);

namespace App\Session\Api\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    #[Route('/session/create/', methods: ['POST'])]
    public function process(Request $request, Command $command): Response
    {
        return $this->json([]);
    }
}