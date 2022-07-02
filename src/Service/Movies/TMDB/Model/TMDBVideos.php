<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB\Model;

use App\Service\Movies\VideosInterface;

class TMDBVideos implements VideosInterface
{
    private array $results = [];

    public function getVideos(): array
    {
        return $this->results;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function addResult(TMDBVideo $video): self
    {
        $this->results[] = $video;

        return $this;
    }

    public function removeResult(TMDBVideo $video): void
    {
    }
}
