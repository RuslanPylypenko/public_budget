<?php

declare(strict_types=1);

namespace App\Project\Api\Create;

use App\Project\Uploader\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly FileUploader $fileUploader,
    ) {
    }

    #[Route('/projects/', methods: ['POST'])]
    public function handle(Request $request): Response
    {
        $file = $request->files->get('file');

        $uploaded = $this->fileUploader->uploadProjectImage($file, 31);

        return $this->json([]);
    }
}