<?php

use Flarum\Extend;
use Illuminate\Contracts\Events\Dispatcher;
use Vendor\FlarumGitHubUpdates\Listeners\GitHubWebhookListener;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),

    (new Extend\Routes('api'))
        ->post('/github-webhook', 'github.webhook', GitHubWebhookListener::class),

    (new Extend\Locales(__DIR__.'/locale')),

    (new Extend\Settings())
        ->serializeToForum('githubRepos', 'vendor.flarum-github-updates.githubRepos'),

    function (Dispatcher $events) {
        $events->subscribe(GitHubWebhookListener::class);
    },
];
