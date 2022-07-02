<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Movies\MoviesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private MoviesService $moviesService;

    public function __construct(MoviesService $moviesService)
    {
        $this->moviesService = $moviesService;
    }

    #[Route('/', name: 'app_default')]
    public function index(Request $request): Response
    {
        $query = $request->query->get('q', null);

        return $this->listing(null, $query ?: null);
    }

    #[Route('/gender/{genderId}', name: 'app_gender')]
    public function gender(string $genderId): Response
    {
        return $this->listing((int) $genderId);
    }

    #[Route('/movie/{movieId}', name: 'app_movie')]
    public function movie(string $movieId): Response
    {
        return $this->render('default/movie.html.twig', ['movieId' => (int) $movieId]);
    }

    public function listing(?int $genderId, ?string $query = null): Response
    {
        return $this->render(
            'default/index.html.twig',
            [
                'genderId' => $genderId,
                'movies' => $this->moviesService->getMovies($genderId, $query),
                'genders' => $this->moviesService->getGenders(),
            ]
        );
    }
}
