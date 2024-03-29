<?php

namespace App\Modules\Request\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      @OA\Xml(name="RequestUpdateRequest"),
 *      title="RequestUpdateRequest",
 *      type="object",
 *      required={"comment"}
 * )
 * @OA\Property(format="string",  property="comment"),
 */
class RequestUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string',
        ];
    }
}
