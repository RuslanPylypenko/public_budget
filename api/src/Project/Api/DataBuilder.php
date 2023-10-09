<?php

namespace App\Project\Api;

use App\Project\ProjectEntity as Project;

class DataBuilder
{
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
            'short'       => $project->getShort(),
            'author'      => [
                'name' => $project->getAuthor()->getFullName()
            ],
            'address'     => $project->getAddress()?->getLocalAddress(),
            'create_date' => $project->getCreateDate(),
        ];

        if ($extend) {
            $data['description'] = $project->getDescription();
        }

        return $data;
    }
}