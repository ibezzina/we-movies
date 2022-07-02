<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface MovieInterface
{
    public function getId(): int;

    public function getTitle(): string;

    public function getReleaseDate(): string;

    public function getVoteAverage(): float;

    public function getVoteCount(): int;

    public function getTrailer(): ?VideoInterface;
}
