<?php
declare(strict_types=1);

namespace App\Service\Movies\Exception;

class GendersFailException extends \Exception
{
    /** @var string */
    protected $message = 'Get genders failed';
}
