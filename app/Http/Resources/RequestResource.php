<?php

namespace App\Http\Resources;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *  @OA\Xml(name="RequestResource")
 * )
 * @OA\Property(format="int64",  property="id"),
 * @OA\Property(format="string", property="name"),
 * @OA\Property(format="string", property="email"),
 * @OA\Property(format="string", property="status")
 * @OA\Property(format="string", property="message")
 * @OA\Property(format="string", property="comment")
 * @OA\Property(format="string", property="created_at")
 * @OA\Property(format="string", property="updated_at")
 */
class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 

     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(HttpRequest $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'message' => $this->message,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
