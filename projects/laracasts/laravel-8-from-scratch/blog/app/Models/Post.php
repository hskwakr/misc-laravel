<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use SplFileInfo;

class Post
{
    public $title;
    public $date;
    public $excerpt;
    public $body;
    public $slug;

    public function __construct($title, $date, $excerpt, $body, $slug)
    {
        $this->title = $title;
        $this->date = $date;
        $this->excerpt = $excerpt;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function all()
    {
        return cache()->remember('post.all', now()->addMinutes(20), function () {
            return collect(File::files(resource_path("posts/")))
                ->map(function (SplFileInfo $file) {
                    $document = YamlFrontMatter::parseFile($file);
                    $slug = str_replace(".html", "", $file->getFilename());;

                    return new Post(
                        $document->title,
                        $document->date,
                        $document->excerpt,
                        $document->body(),
                        $slug,
                    );
                })
                ->sortByDesc('date');
        });
    }

    public static function find($slug)
    {
        return static::all()->firstWhere('slug', $slug)
            ?? throw new ModelNotFoundException();
    }
}
