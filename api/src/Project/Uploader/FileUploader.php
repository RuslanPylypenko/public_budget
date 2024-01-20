<?php

namespace App\Project\Uploader;

use App\Project\ProjectEntity;
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

    public function uploadProjectImage(UploadedFile $file, ProjectEntity $project): File
    {
        return $this->upload($file, sprintf('%s/%s/images', $project->getSession()->getId(), $project->getNumber()));
    }

    /**
     * @param UploadedFile[] $newImages
     */
    public function updateProjectImages(ProjectEntity $project, array $newImages): void
    {
        foreach ($newImages as $newImage) {
            $newImageHash = $this->storage->checksum($newImage->getPathname());

            foreach ($project->getImages() as $existingPhoto) {
                $existingImageHash = $this->storage->checksum($existingPhoto);

                if ($newImageHash === $existingImageHash) {
                    continue 2;
                }
            }

            $uploaded = $this->uploadProjectImage($newImage, $project);

            $project->addImage($uploaded->getFileName());
        }


        $removedImages = $this->deleteUnmatchedExistingPhotos($project, $newImages);

        foreach ($removedImages as $removedImage){
            $project->removeImage($removedImage);
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

    public function remove(string $path): void
    {
        $this->storage->delete($path);
    }

    /**
     * @param UploadedFile[] $newImages
     * @return array<string>
     */
    private function deleteUnmatchedExistingPhotos(ProjectEntity $project, array $newImages): array
    {
        $removedImages = [];
        foreach ($project->getImages() as $existingImage) {
            $existingImageHash = $this->storage->checksum($existingImage);
            $matched = false;

            foreach ($newImages as $newImage) {
                $newPhotoHash = $this->storage->checksum($newImage->getPathname());

                if ($newPhotoHash === $existingImageHash) {
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $this->remove($existingImage);
                $removedImages[] = $removedImages;
            }
        }

        return $removedImages;
    }
}