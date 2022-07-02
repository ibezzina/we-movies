<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface MoviesClientInterface
{
    public function getGenders(): GendersInterface;

    public function getMovies(?int $genderId): MoviesInterface;

    public function searchMovies(string $query): MoviesInterface;

    public function getMovieVideos(int $movieId): VideosInterface;
}
