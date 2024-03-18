<?php

namespace App\Modules\Request\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      @OA\Xml(name="RequestStoreRequest"),
 *      title="RequestStoreRequest",
 *      type="object",
 *      required={"name", "email", "message"}
 * )
 * @OA\Property(format="string",  property="name"),
 * @OA\Property(format="string",  property="email"),
 * @OA\Property(format="string",  property="message"),
 */
class RequestStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ];
    }
}
