<?php

namespace Bdawg1989\FlarumGitHubUpdates\Repositories;

use Flarum\Discussion\Discussion;
use Flarum\Tag\Tag;
use Illuminate\Database\Eloquent\Builder;

class GitHubRepository
{
    public function getDiscussionIdByTag($tag)
    {
        $tag = Tag::where('name', $tag)->first();

        if ($tag) {
            $discussion = Discussion::whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })->first();

            return $discussion ? $discussion->id : null;
        }

        return null;
    }
}
