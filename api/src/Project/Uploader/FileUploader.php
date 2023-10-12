<?php

namespace App\Project\Uploader;

use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private readonly FilesystemOperator $storage,
        private readonly string $baseUrl,
    ) {
    }

    public function uploadProjectImage(UploadedFile $file, int $projectId): File
    {
        return $this->upload($file, 'images/' . $projectId);
    }

    private function upload(UploadedFile $file, string $path): File
    {
        $name = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        $this->storage->createDirectory($path);
        $stream = fopen($file->getRealPath(), 'rb+');
        $this->storage->writeStream($path . '/' . $name, $stream);
        fclose($stream);

        return new File($path, $name, $file->getSize());
    }

    public function generateUrl(string $path): string
    {
        return $this->baseUrl . '/' . $path;
    }

    public function remove(string $path, string $name): void
    {
        $this->storage->delete($path . '/' . $name);
    }
}