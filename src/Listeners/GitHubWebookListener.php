<?php

namespace Bdawg1989\FlarumGitHubUpdates\Listeners;

use Flarum\Post\Command\PostReply;
use Flarum\User\User;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Bdawg1989\FlarumGitHubUpdates\Repositories\GitHubRepository;

class GitHubWebhookListener
{
    protected $bus;
    protected $repository;

    public function __construct(Dispatcher $bus, GitHubRepository $repository)
    {
        $this->bus = $bus;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $payload = json_decode($request->getBody()->getContents(), true);
        $repository = Arr::get($payload, 'repository.full_name');
        $commitMessage = Arr::get($payload, 'head_commit.message');
        $commitUrl = Arr::get($payload, 'head_commit.url');
        $tag = 'GitHub Updates'; // Replace with your desired tag

        if ($repository && $commitMessage && $commitUrl) {
            $this->postUpdate($repository, $commitMessage, $commitUrl, $tag);
        }

        return new JsonResponse(['status' => 'ok']);
    }

    protected function postUpdate($repository, $commitMessage, $commitUrl, $tag)
    {
        $actor = User::find(1); // Assuming user ID 1 is the admin or bot user

        $data = [
            'type' => 'posts',
            'attributes' => [
                'content' => "New update in [{$repository}]({$commitUrl}):\n\n{$commitMessage}"
            ],
            'relationships' => [
                'discussion' => [
                    'data' => [
                        'type' => 'discussions',
                        'id' => $this->repository->getDiscussionIdByTag($tag)
                    ]
                ]
            ]
        ];

        $this->bus->dispatch(
            new PostReply($actor, $data)
        );
    }
}
