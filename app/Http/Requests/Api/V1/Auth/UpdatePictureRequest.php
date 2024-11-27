<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="UpdatePictureRequestBody",
 *     required=true,
 *
 *     @OA\MediaType(
 *         mediaType="multipart/form-data",
 *
 *         @OA\Schema(
 *             required={"picture"},
 *
 *             @OA\Property(property="picture", type="string", format="binary")
 *         )
 *     )
 * )
 */
class UpdatePictureRequest extends FormRequest
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
            'picture' => 'required|image|max:2048',
        ];
    }
}
