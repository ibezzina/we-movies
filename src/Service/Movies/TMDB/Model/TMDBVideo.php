<?php
declare(strict_types=1);

namespace App\Service\Movies\TMDB\Model;

use App\Service\Movies\VideoInterface;

class TMDBVideo implements VideoInterface
{
    public const SITE_YOUTUBE = 'YouTube';
    public const TYPE_TRAILER = 'Trailer';

    private string $name;
    private string $site;
    private string $key;
    private string $type;

    public function getTitle(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): string
    {
        if (self::SITE_YOUTUBE === $this->site) {
            return 'https://www.youtube.com/embed/' . $this->key;
        }

        throw new \Exception(sprintf('unsupported site "%s"', $this->site));
    }

    public function isTrailer(): bool
    {
        return self::TYPE_TRAILER === $this->getType();
    }
}
