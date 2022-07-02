<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface MoviesInterface
{
    public function getMovies(): array;
}
