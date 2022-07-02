<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB\Model;

use App\Service\Movies\MovieInterface;
use App\Service\Movies\VideoInterface;
use App\Service\Movies\VideosInterface;

class TMDBMovie implements MovieInterface
{
    private int $id;
    private string $title;
    private ?string $backdropPath;
    private array $genreIds = [];
    private string $overview;
    private string $releaseDate = '';
    private float $voteAverage;
    private int $voteCount;
    private ?VideosInterface $videos = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBackdropPath(): ?string
    {
        return $this->backdropPath;
    }

    public function setBackdropPath(?string $backdropPath): self
    {
        $this->backdropPath = $backdropPath;

        return $this;
    }

    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    public function setGenreIds(array $genreIds): self
    {
        $this->genreIds = $genreIds;

        return $this;
    }

    public function getOverview(): string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function getYear(): int
    {
        return (int) substr($this->releaseDate, 0, 4);
    }

    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getVoteAverage(): float
    {
        return $this->voteAverage;
    }

    public function setVoteAverage(float $voteAverage): self
    {
        $this->voteAverage = $voteAverage;

        return $this;
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): self
    {
        $this->voteCount = $voteCount;

        return $this;
    }

    public function setVideos(VideosInterface $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    public function getTrailer(): ?VideoInterface
    {
        if (null === $this->videos) {
            return null;
        }

        foreach ($this->videos->getVideos() as $video) {
            if ($video->isTrailer()) {
                return $video;
            }
        }

        return null;
    }
}
