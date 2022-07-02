<?php
declare(strict_types=1);

namespace App\Service\Movies\Exception;

class SearchMoviesFailException extends \Exception
{
    /** @var string */
    protected $message = 'Search movie failed';
}
