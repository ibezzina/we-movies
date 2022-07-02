<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface VideosInterface
{
    public function getVideos(): array;
}
