<?php

namespace App\Project\Api;

use App\Project\ProjectEntity as Project;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class DataBuilder
{
    public function __construct(
        private readonly ContainerBagInterface $containerBag,
    ) {
    }

    /**
     * @param Project[] $projects
     * @return array
     */
    public function projects(array $projects): array
    {
        return array_map(fn($project) => $this->project($project), $projects);
    }

    public function project(Project $project, $extend = false): array
    {
        $data = [
            'name'        => $project->getName(),
            'number'      => $project->getNumber(),
            'status'      => $project->getStatus(),
            'budget'      => $project->getBudget(),
            'author'      => [
                'name' => $project->getAuthor()->getFullName()
            ],
            'address'     => $project->getAddress()?->getLocalAddress(),
            'create_date' => $project->getCreateDate(),
        ];

        if ($extend) {
            $data['short'] = $project->getShort();
            $data['description'] = $project->getDescription();
            $data['images'] = array_map(fn($image) => $this->buildImageUrl($image) , $project->getImages());
        }

        return $data;
    }

    private function buildImageUrl($image): string
    {
        return $this->containerBag->get('app.storage_base_url') . $image;
    }
}