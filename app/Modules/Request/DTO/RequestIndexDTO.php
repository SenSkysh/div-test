<?php

namespace App\Modules\Request\DTO;

use App\Modules\Base\DTO\BaseDTO;
use App\Modules\Request\Requests\RequestIndexRequest;

class RequestIndexDTO extends BaseDto
{
    public ?string $status;
    public ?string $from;
    public ?string $to;
}