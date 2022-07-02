<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB\Model;

use App\Service\Movies\GendersInterface;

class TMDBGenders implements GendersInterface
{
    /** @var TMDBGender[] */
    private array $genres = [];

    /**
     * @return TMDBGender[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    public function addGenre(TMDBGender $genre): void
    {
        $this->genres[$genre->getId()] = $genre;
    }

    public function removeGenre(TMDBGender $genre): void
    {
    }

    public function getGenders(): array
    {
        return $this->genres;
    }

    public function getGender(int $id): TMDBGender
    {
        return $this->genres[$id];
    }
}
