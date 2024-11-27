<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="SuccessResponse",
     *     type="object",
     *
     *     @OA\Property(property="success", type="boolean", example=true),
     *     @OA\Property(property="message", type="string", example="Operation successful"),
     *     @OA\Property(property="data", type="object", nullable=true)
     * )
     */
    protected function successResponse(array $data = [], ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => '',
            'data' => [],
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     type="object",
     *
     *     @OA\Property(property="success", type="boolean", example=false),
     *     @OA\Property(property="message", type="string", example="An error occurred"),
     *     @OA\Property(property="errors", type="object", nullable=true, @OA\AdditionalProperties(type="array", @OA\Items(type="string")))
     * )
     */
    protected function errorResponse(string $message, int $statusCode = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
