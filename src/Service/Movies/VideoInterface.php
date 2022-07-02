<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface VideoInterface
{
    public function getTitle(): string;

    public function getUrl(): string;

    public function getType(): string;
}
