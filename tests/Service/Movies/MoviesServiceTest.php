<?php
declare(strict_types=1);

namespace App\Tests\Service\Movies;

use App\Service\Movies\Exception\GendersFailException;
use App\Service\Movies\MoviesClientInterface;
use App\Service\Movies\MoviesService;
use App\Service\Movies\TMDB\Model\TMDBGenders;
use App\Service\Movies\TMDB\Model\TMDBMovie;
use App\Service\Movies\TMDB\Model\TMDBMovies;
use App\Service\Movies\TMDB\Model\TMDBVideo;
use App\Service\Movies\TMDB\Model\TMDBVideos;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MoviesServiceTest extends TestCase
{
    /**
     * @var MoviesClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private mixed $moviesClient;
    private MoviesService $moviesService;

    protected function setUp(): void
    {
        $this->moviesClient = $this->createMock(MoviesClientInterface::class);
        $this->moviesService = new MoviesService($this->moviesClient);
    }

    public function test_get_genders(): void
    {
        $return = new TMDBGenders();
        $this->moviesClient->expects(static::once())
            ->method('getGenders')
            ->willReturn($return)
        ;

        $genders = $this->moviesService->getGenders();

        static::assertSame($return, $genders);
    }

    public function test_get_genders_exception(): void
    {
        $this->moviesClient->expects(static::once())
            ->method('getGenders')
            ->willThrowException(new GendersFailException())
        ;

        self::expectException(GendersFailException::class);
        $genders = $this->moviesService->getGenders();
    }

    public function test_get_movies(): void
    {
        $tmdbMovie = (new TMDBMovie())
            ->setId(1)
            ->setTitle('title')
            ->setBackdropPath('path')
            ->setGenreIds([1, 2])
            ->setOverview('overview')
            ->setReleaseDate('2022-07-03')
            ->setVoteAverage(1)
            ->setVoteCount(10)
        ;
        $tmdbMovies = (new TMDBMovies())->addResult($tmdbMovie);
        $this->moviesClient->expects(static::once())
            ->method('searchMovies')
            ->willReturn($tmdbMovies)
        ;

        $tmdbVideo = (new TMDBVideo())
            ->setKey('key')
            ->setName('mane')
            ->setSite('site')
            ->setType('type')
        ;
        $tmdbVideos = (new TMDBVideos())->addResult($tmdbVideo);
        $this->moviesClient->expects(static::once())
            ->method('getMovieVideos')
            ->willReturn($tmdbVideos)
        ;

        $movies = $this->moviesService->getMovies(1, 'test');

        static::assertSame($tmdbMovies, $movies);
        $movie = $movies->getMovies()[0];
        static::assertSame($tmdbMovie, $movie);
    }
}
