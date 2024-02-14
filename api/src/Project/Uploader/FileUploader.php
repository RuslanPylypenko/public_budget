<?php

declare(strict_types=1);

namespace App\Project\Uploader;

use App\Project\ProjectEntity;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FileUploader
{
    public function __construct(
        private FilesystemOperator $storage,
        private string             $baseUrl,
    ) {
    }

    public function uploadProjectImage(UploadedFile $file, ProjectEntity $project): File
    {
        return $this->upload($file, sprintf('%s/%s/images', $project->getSession()->getId(), $project->getNumber()));
    }

    /**
     * @param UploadedFile[] $uploadedImages
     */
    public function updateProjectImages(ProjectEntity $project, array $uploadedImages): void
    {
        $requestImages = $existingImages = [];
        foreach ($project->getImages() as $image) {
            $existingImages[$this->storage->checksum($image)] = $image;
        }

        foreach ($uploadedImages as $image) {
            $requestImages[md5_file($image->getPathname())] = $image;
        }

        $toUpload = array_diff(array_keys($requestImages), array_keys($existingImages));
        $toRemove = array_diff(array_keys($existingImages), array_keys($requestImages));

        foreach ($toUpload as $hash){
            $uploaded = $this->uploadProjectImage($requestImages[$hash], $project);
            $project->addImage($uploaded->getFileName());
        }

        foreach ($toRemove as $hash){
            $project->removeImage($existingImages[$hash]);
            $this->removeFile($existingImages[$hash]);
        }
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

    public function removeFile(string $path): void
    {
        if ($this->storage->fileExists($path)) {
            $this->storage->delete($path);
        }
    }
}