<?php

namespace App\Modules\Request\DTO;

use App\Modules\Base\DTO\BaseDTO;
use App\Modules\Request\Requests\RequestUpdateRequest;

class RequestResolveDTO extends BaseDto
{
    public int $id;
    public string $comment;
}