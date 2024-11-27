<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="UpdatePasswordRequestBody",
 *     required=true,
 *
 *     @OA\JsonContent(
 *         required={"current_password", "new_password"},
 *
 *         @OA\Property(property="current_password", type="string", example="oldpassword123"),
 *         @OA\Property(property="new_password", type="string", example="newpassword123")
 *     )
 * )
 */
class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ];
    }
}
