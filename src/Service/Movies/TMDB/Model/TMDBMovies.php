<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB\Model;

use App\Service\Movies\MoviesInterface;

class TMDBMovies implements MoviesInterface
{
    private array $results = [];

    public function getMovies(): array
    {
        return $this->results;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function addResult(TMDBMovie $movie): self
    {
        $this->results[] = $movie;

        return $this;
    }

    public function removeResult(TMDBMovie $movie): void
    {
    }
}
