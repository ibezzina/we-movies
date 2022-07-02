<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB;

use App\Service\Movies\Exception\GendersFailException;
use App\Service\Movies\Exception\MoviesFailException;
use App\Service\Movies\Exception\SearchMoviesFailException;
use App\Service\Movies\Exception\VideosFailException;
use App\Service\Movies\GendersInterface;
use App\Service\Movies\MoviesClientInterface;
use App\Service\Movies\MoviesInterface;
use App\Service\Movies\TMDB\Model\TMDBGenders;
use App\Service\Movies\TMDB\Model\TMDBMovies;
use App\Service\Movies\TMDB\Model\TMDBVideos;
use App\Service\Movies\VideosInterface;
use App\Traits\SerializerTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBClient implements MoviesClientInterface
{
    use SerializerTrait;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $TMDBClient)
    {
        $this->client = $TMDBClient;
    }

    /**
     * @throws GendersFailException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getGenders(): GendersInterface
    {
        $response = $this->client->request(Request::METHOD_GET, 'genre/movie/list');

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new GendersFailException($response->getContent(false));
        }

        return $this->deserialize($response->getContent(false), TMDBGenders::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws MoviesFailException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovies(?int $genderId = null): MoviesInterface
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            'discover/movie',
            ['query' => ['with_genres' => $genderId]]
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new MoviesFailException($response->getContent(false));
        }

        return $this->deserialize($response->getContent(false), TMDBMovies::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws SearchMoviesFailException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function searchMovies(string $query): MoviesInterface
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            'search/movie',
            ['query' => ['query' => $query]]
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new SearchMoviesFailException($response->getContent(false));
        }

        return $this->deserialize($response->getContent(false), TMDBMovies::class);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws VideosFailException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovieVideos(int $movieId): VideosInterface
    {
        $response = $this->client->request(Request::METHOD_GET, sprintf('movie/%d/videos', $movieId));

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new VideosFailException($response->getContent(false));
        }

        return $this->deserialize($response->getContent(false), TMDBVideos::class);
    }
}
