<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRepositoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [];
        if(isset($this['id'])){
            $resource = [
                'id'                        => $this['id'],
                'name'                      => $this['name'],
                'private'                   => $this['private'],
                'ower_username'             => $this['owner']['login'],
                'description'               => $this['description'],
                'created_at'                => $this['created_at'],
                'updated_at'                => $this['updated_at'],
                'pushed_at'                 => $this['pushed_at'],
                'size'                      => $this['size'],
                'stargazers_count'          => $this['stargazers_count'],
                'watchers_count'            => $this['watchers_count'],
                'language'                  => $this['language'],
                'has_issues'                => $this['has_issues'],
                'has_projects'              => $this['has_projects'],
                'has_downloads'             => $this['has_downloads'],
                'has_wiki'                  => $this['has_wiki'],
                'has_pages'                 => $this['has_pages'],
                'has_discussions'           => $this['has_discussions'],
                'forks_count'               => $this['forks_count'],
                'archived'                  => $this['archived'],
                'disabled'                  => $this['disabled'],
                'open_issues_count'         => $this['open_issues_count'],
                'license'                   => $this['license'],
                'topics'                    => $this['topics'],
                'allow_forking'             => $this['allow_forking'],
                'is_template'               => $this['is_template'],
                'web_commit_signoff_required' => $this['web_commit_signoff_required'],
                'visibility'                => $this['visibility'],
                'forks'                     => $this['forks'],
                'open_issues'               => $this['open_issues'],
                'watchers'                  => $this['watchers'],
                'default_branch'            => $this['default_branch'],
                'permissions'               => $this['permissions']
            ];
        }

        return $resource;

    }
}
