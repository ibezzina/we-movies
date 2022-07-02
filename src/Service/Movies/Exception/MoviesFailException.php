<?php
declare(strict_types=1);

namespace App\Service\Movies\Exception;

class MoviesFailException extends \Exception
{
    /** @var string */
    protected $message = 'Get movies failed';
}
