<?php
declare(strict_types=1);

namespace App\Service\Movies;

interface GenderInterface
{
    public function getId(): int;

    public function getName(): string;
}
