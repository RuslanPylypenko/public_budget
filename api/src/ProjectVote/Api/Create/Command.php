<?php

namespace App\ProjectVote\Api\Create;

use App\Api\InputInterface;
use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface
{
    #[Assert\NotBlank]
    #[SerializedPath('[session_id]')]
    public int $sessionId;

    #[Assert\NotBlank]
    #[SerializedPath('[project_number]')]
    public int $projectNumber;

}