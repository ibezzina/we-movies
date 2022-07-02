<?php
declare(strict_types=1);

namespace App\Tests\Service\Movies\TMDB;

use App\Service\Movies\Exception\GendersFailException;
use App\Service\Movies\Exception\MoviesFailException;
use App\Service\Movies\Exception\SearchMoviesFailException;
use App\Service\Movies\Exception\VideosFailException;
use App\Service\Movies\GenderInterface;
use App\Service\Movies\GendersInterface;
use App\Service\Movies\MovieInterface;
use App\Service\Movies\MoviesInterface;
use App\Service\Movies\TMDB\TMDBClient;
use App\Service\Movies\VideoInterface;
use App\Service\Movies\VideosInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class TMDBServiceTest extends TestCase
{
    public function test_get_genders(): void
    {
        $TMDBClient = $this->createClient('{"genres": [{"id": 28, "name": "Action"}, {"id": 12, "name": "Adventure"}]}');
        $genders = $TMDBClient->getGenders();

        static::assertInstanceOf(GendersInterface::class, $genders);

        $genders = $genders->getGenders();
        static::assertCount(2, $genders);
        static::assertSame([28, 12], array_keys($genders));

        static::assertInstanceOf(GenderInterface::class, $genders[28]);
        static::assertSame(28, $genders[28]->getId());
        static::assertSame('Action', $genders[28]->getName());

        static::assertInstanceOf(GenderInterface::class, $genders[12]);
        static::assertSame(12, $genders[12]->getId());
        static::assertSame('Adventure', $genders[12]->getName());
    }

    public function test_get_genders_exception(): void
    {
        $TMDBClient = $this->createClient('', Response::HTTP_INTERNAL_SERVER_ERROR);

        static::expectException(GendersFailException::class);
        $genders = $TMDBClient->getGenders();
    }

    /**
     * @dataProvider GetMovieDataProvider
     */
    public function test_get_movies(?int $genderId): void
    {
        $TMDBClient = $this->createClient('{
            "page": 1,
            "results": [
                {
                    "adult": false,
                    "backdrop_path": "/wcKFYIiVDvRURrzglV9kGu7fpfY.jpg",
                    "genre_ids": [
                        14,
                        28,
                        12
                    ],
                    "id": 453395,
                    "original_language": "en",
                    "original_title": "Doctor Strange in the Multiverse of Madness",
                    "overview": "Le Docteur Stephen Strange continue ses recherches sur la Pierre du Temps. Cependant, un vieil ami devenu ennemi tente de d\u00e9truire tous les sorciers de la Terre, ce qui perturbe le plan de Strange.",
                    "popularity": 9929.649,
                    "poster_path": "/dbJDPJBHKxnMyvcc12mcbGK5RPF.jpg",
                    "release_date": "2022-05-04",
                    "title": "Doctor Strange in the Multiverse of Madness",
                    "video": false,
                    "vote_average": 7.5,
                    "vote_count": 3851
                },
                {
                    "adult": false,
                    "backdrop_path": "/zGLHX92Gk96O1DJvLil7ObJTbaL.jpg",
                    "genre_ids": [
                        14,
                        12,
                        28
                    ],
                    "id": 338953,
                    "original_language": "en",
                    "original_title": "Fantastic Beasts: The Secrets of Dumbledore",
                    "overview": "Le professeur Albus Dumbledore sait que le puissant mage noir Gellert Grindelwald cherche \u00e0 prendre le contr\u00f4le du monde des sorciers. Incapable de l\u2019emp\u00eacher d\u2019agir seul, il sollicite le magizoologiste Norbert Dragonneau pour qu\u2019il r\u00e9unisse des sorciers, des sorci\u00e8res et un boulanger moldu au sein d\u2019une \u00e9quipe intr\u00e9pide. Leur mission des plus p\u00e9rilleuses les am\u00e8nera \u00e0 affronter des animaux, anciens et nouveaux, et les disciples de plus en plus nombreux de Grindelwald. Pourtant, d\u00e8s lors que que les enjeux sont aussi \u00e9lev\u00e9s, Dumbledore pourra-t-il encore rester longtemps dans l\u2019ombre ?",
                    "popularity": 2910.111,
                    "poster_path": "/uXs7wMtsfnBFuGVogAxJXZXshFU.jpg",
                    "release_date": "2022-04-06",
                    "title": "Les Animaux fantastiques : Les Secrets de Dumbledore",
                    "video": false,
                    "vote_average": 6.8,
                    "vote_count": 2104
                }
            ],
            "total_pages": 34178,
            "total_results": 683551
        }');
        $movies = $TMDBClient->getMovies($genderId);

        static::assertInstanceOf(MoviesInterface::class, $movies);

        $movies = $movies->getMovies();
        static::assertCount(2, $movies);

        static::assertInstanceOf(MovieInterface::class, $movies[0]);
        static::assertSame(453395, $movies[0]->getId());
        static::assertSame('Doctor Strange in the Multiverse of Madness', $movies[0]->getTitle());
        static::assertSame('2022-05-04', $movies[0]->getReleaseDate());
        static::assertSame(7.5, $movies[0]->getVoteAverage());
        static::assertSame(3851, $movies[0]->getVoteCount());

        static::assertInstanceOf(MovieInterface::class, $movies[1]);
        static::assertSame(338953, $movies[1]->getId());
        static::assertSame('Les Animaux fantastiques : Les Secrets de Dumbledore', $movies[1]->getTitle());
        static::assertSame('2022-04-06', $movies[1]->getReleaseDate());
        static::assertSame(6.8, $movies[1]->getVoteAverage());
        static::assertSame(2104, $movies[1]->getVoteCount());
    }

    /**
     * @dataProvider GetMovieDataProvider
     */
    public function test_get_movies_exception(?int $genderId): void
    {
        $TMDBClient = $this->createClient('', Response::HTTP_INTERNAL_SERVER_ERROR);

        static::expectException(MoviesFailException::class);
        $movies = $TMDBClient->getMovies($genderId);
    }

    public function GetMovieDataProvider(): \Generator
    {
        yield 'null genderId' => [null];
        yield 'zero genderId' => [0];
        yield 'not null genderID' => [1];
    }

    /**
     * @dataProvider SearchMovieDataProvider
     */
    public function test_search_movies(string $query): void
    {
        $TMDBClient = $this->createClient('{
            "page": 1,
            "results": [
                {
                    "adult": false,
                    "backdrop_path": "/wcKFYIiVDvRURrzglV9kGu7fpfY.jpg",
                    "genre_ids": [
                        14,
                        28,
                        12
                    ],
                    "id": 453395,
                    "original_language": "en",
                    "original_title": "Doctor Strange in the Multiverse of Madness",
                    "overview": "Le Docteur Stephen Strange continue ses recherches sur la Pierre du Temps. Cependant, un vieil ami devenu ennemi tente de d\u00e9truire tous les sorciers de la Terre, ce qui perturbe le plan de Strange.",
                    "popularity": 9929.649,
                    "poster_path": "/dbJDPJBHKxnMyvcc12mcbGK5RPF.jpg",
                    "release_date": "2022-05-04",
                    "title": "Doctor Strange in the Multiverse of Madness",
                    "video": false,
                    "vote_average": 7.5,
                    "vote_count": 3851
                },
                {
                    "adult": false,
                    "backdrop_path": "/zGLHX92Gk96O1DJvLil7ObJTbaL.jpg",
                    "genre_ids": [
                        14,
                        12,
                        28
                    ],
                    "id": 338953,
                    "original_language": "en",
                    "original_title": "Fantastic Beasts: The Secrets of Dumbledore",
                    "overview": "Le professeur Albus Dumbledore sait que le puissant mage noir Gellert Grindelwald cherche \u00e0 prendre le contr\u00f4le du monde des sorciers. Incapable de l\u2019emp\u00eacher d\u2019agir seul, il sollicite le magizoologiste Norbert Dragonneau pour qu\u2019il r\u00e9unisse des sorciers, des sorci\u00e8res et un boulanger moldu au sein d\u2019une \u00e9quipe intr\u00e9pide. Leur mission des plus p\u00e9rilleuses les am\u00e8nera \u00e0 affronter des animaux, anciens et nouveaux, et les disciples de plus en plus nombreux de Grindelwald. Pourtant, d\u00e8s lors que que les enjeux sont aussi \u00e9lev\u00e9s, Dumbledore pourra-t-il encore rester longtemps dans l\u2019ombre ?",
                    "popularity": 2910.111,
                    "poster_path": "/uXs7wMtsfnBFuGVogAxJXZXshFU.jpg",
                    "release_date": "2022-04-06",
                    "title": "Les Animaux fantastiques : Les Secrets de Dumbledore",
                    "video": false,
                    "vote_average": 6.8,
                    "vote_count": 2104
                }
            ],
            "total_pages": 34178,
            "total_results": 683551
        }');
        $movies = $TMDBClient->searchMovies($query);

        static::assertInstanceOf(MoviesInterface::class, $movies);

        $movies = $movies->getMovies();
        static::assertCount(2, $movies);

        static::assertInstanceOf(MovieInterface::class, $movies[0]);
        static::assertSame(453395, $movies[0]->getId());
        static::assertSame('Doctor Strange in the Multiverse of Madness', $movies[0]->getTitle());
        static::assertSame('2022-05-04', $movies[0]->getReleaseDate());
        static::assertSame(7.5, $movies[0]->getVoteAverage());
        static::assertSame(3851, $movies[0]->getVoteCount());

        static::assertInstanceOf(MovieInterface::class, $movies[1]);
        static::assertSame(338953, $movies[1]->getId());
        static::assertSame('Les Animaux fantastiques : Les Secrets de Dumbledore', $movies[1]->getTitle());
        static::assertSame('2022-04-06', $movies[1]->getReleaseDate());
        static::assertSame(6.8, $movies[1]->getVoteAverage());
        static::assertSame(2104, $movies[1]->getVoteCount());
    }

    /**
     * @dataProvider SearchMovieDataProvider
     */
    public function test_search_movies_exception(string $query): void
    {
        $TMDBClient = $this->createClient('', Response::HTTP_INTERNAL_SERVER_ERROR);

        static::expectException(SearchMoviesFailException::class);
        $movies = $TMDBClient->searchMovies($query);
    }

    public function SearchMovieDataProvider(): \Generator
    {
        yield 'empty query' => [''];
        yield 'not empty query' => ['test'];
    }

    public function test_get_movie_videos(): void
    {
        $TMDBClient = $this->createClient('{
            "id": 453395,
            "results": [
                {
                    "iso_639_1": "fr",
                    "iso_3166_1": "FR",
                    "name": "Doctor Strange in the Multiverse of Madness - Extrait : Wong et la cr\u00e9ature [VOST]",
                    "key": "skP0h0irI1k",
                    "site": "YouTube",
                    "size": 1080,
                    "type": "Clip",
                    "official": true,
                    "published_at": "2022-05-19T17:00:35.000Z",
                    "id": "62867a2b1f3e60009b7263c7"
                },
                {
                    "iso_639_1": "fr",
                    "iso_3166_1": "FR",
                    "name": "Doctor Strange in the Multiverse of Madness - Extrait : Wong et la cr\u00e9ature [VF]",
                    "key": "TWIA_v0003w",
                    "site": "YouTube",
                    "size": 1080,
                    "type": "Trailer",
                    "official": true,
                    "published_at": "2022-05-19T17:00:13.000Z",
                    "id": "62867a106d9fe85498b18a77"
                }
            ]
        }');
        $videos = $TMDBClient->getMovieVideos(1);

        static::assertInstanceOf(VideosInterface::class, $videos);

        $videos = $videos->getVideos();
        static::assertCount(2, $videos);

        static::assertInstanceOf(VideoInterface::class, $videos[0]);
        static::assertSame('Doctor Strange in the Multiverse of Madness - Extrait : Wong et la créature [VOST]', $videos[0]->getTitle());
        static::assertSame('https://www.youtube.com/embed/skP0h0irI1k', $videos[0]->geturl());
        static::assertSame('Clip', $videos[0]->getType());

        static::assertInstanceOf(VideoInterface::class, $videos[1]);
        static::assertSame('Doctor Strange in the Multiverse of Madness - Extrait : Wong et la créature [VF]', $videos[1]->getTitle());
        static::assertSame('https://www.youtube.com/embed/TWIA_v0003w', $videos[1]->geturl());
        static::assertSame('Trailer', $videos[1]->getType());
    }

    public function test_get_movie_videos_exception(): void
    {
        $TMDBClient = $this->createClient('', Response::HTTP_INTERNAL_SERVER_ERROR);

        static::expectException(VideosFailException::class);
        $movies = $TMDBClient->getMovieVideos(1);
    }

    private function createClient(string $body, int $statusCode = Response::HTTP_OK): TMDBClient
    {
        return new TMDBClient(
            new MockHttpClient(
                new MockResponse($body, ['http_code' => $statusCode]),
                'http://mock'
            )
        );
    }
}
