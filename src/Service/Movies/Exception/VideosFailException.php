<?php
declare(strict_types=1);

namespace App\Service\Movies\Exception;

class VideosFailException extends \Exception
{
    /** @var string */
    protected $message = 'Get video failed';
}
