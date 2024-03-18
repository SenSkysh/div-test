<?php

namespace App\Modules\Request\DTO;

use App\Modules\Base\DTO\BaseDTO;

use App\Modules\Request\Models\Request;
use Carbon\Carbon;


class RequestDTO extends BaseDto
{
    public int $id;
    public string $name;
    public string $email;
    public string $status;
    public string $message;
    public ?string $comment;
    public Carbon $created_at;
    public Carbon $updated_at;


    public static function fromModel(Request $request): self
    {
        return new self(
            id: $request->id,
            name: $request->name,
            email: $request->email,
            status: $request->status,
            message: $request->message,
            comment: $request->comment,
            created_at: $request->created_at,
            updated_at: $request->updated_at,
        );
    }
}