<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="ResetPasswordRequestBody",
 *     required=true,
 *
 *     @OA\JsonContent(
 *         required={"email", "password", "password_confirmation", "token"},
 *
 *         @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *         @OA\Property(property="password", type="string", example="newpassword123"),
 *         @OA\Property(property="password_confirmation", type="string", example="newpassword123"),
 *         @OA\Property(property="token", type="string", example="reset-token-example")
 *     )
 * )
 */
class ResetPasswordRequest extends FormRequest
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
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
