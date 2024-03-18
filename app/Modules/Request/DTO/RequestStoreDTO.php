<?php

namespace App\Modules\Request\DTO;
use App\Modules\Base\DTO\BaseDTO;

class RequestStoreDTO extends BaseDto
{
    public string $name;
    public string $email;
    public string $message;
}