<?php
namespace App\ProjectVote\Api\Create;

use App\Api\Exception\ApiException;
use App\Http\Annotation\Authenticate;
use App\Project\ProjectEntity as Project;
use App\ProjectVote\ProjectVoteEntity as ProjectVote;
use App\User\UserEntity as User;
use Browser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/project/vote/', methods: ['POST']), Authenticate]
    public function handle(Request $request, Command $command, User $user): Response
    {
        $project = $this->em->getRepository(Project::class)->findOneBy([
            'session' => $command->sessionId,
            'number' => $command->projectNumber,
        ]);

        if ($project === null) {
            throw new ApiException('Project not found');
        }

        if(null !== $this->em->getRepository(ProjectVote::class)->findOneBy(['user' => $user, 'project' => $project])){
            throw new ApiException('You have been already voted for this project');
        }

        $projectVote = new ProjectVote(
            $user,
            $project,
            $request->getClientIp(),
            $request->headers->get('User-Agent'),
            (new Browser($request->headers->get('User-Agent')))->getBrowser(),
        );

        $this->em->persist($projectVote);
        $this->em->flush();


        return $this->json([]);
    }
}