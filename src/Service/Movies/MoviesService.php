<?php
declare(strict_types=1);

namespace App\Service\Movies;

use App\Service\Movies\Exception\GendersFailException;
use App\Service\Movies\Exception\MoviesFailException;
use App\Service\Movies\TMDB\Model\TMDBMovie;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoviesService
{
    private MoviesClientInterface $client;

    public function __construct(MoviesClientInterface $moviesClient)
    {
        $this->client = $moviesClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws GendersFailException
     */
    public function getGenders(): GendersInterface
    {
        return $this->client->getGenders();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws MoviesFailException
     */
    public function getMovies(?int $genderId = null, ?string $query = null): MoviesInterface
    {
        if (null !== $query) {
            $movies = $this->client->searchMovies($query);
        } else {
            $movies = $this->client->getMovies($genderId);
        }

        /** @var TMDBMovie $movie */
        foreach ($movies->getMovies() as $movie) {
            $movie->setVideos($this->client->getMovieVideos($movie->getId()));
        }

        return $movies;
    }
}
