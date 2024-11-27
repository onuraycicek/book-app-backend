<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Common\BaseController;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\V1\Auth\UpdatePasswordRequest;
use App\Services\Api\V1\UserPasswordService;
use Illuminate\Validation\ValidationException;

class UserPasswordController extends BaseController
{
    protected $userPasswordService;

    public function __construct(UserPasswordService $userPasswordService)
    {
        $this->userPasswordService = $userPasswordService;
    }

    /**
     * @OA\Post(
     *     path="/password/forgot",
     *     tags={"User Password"},
     *     summary="Send a password reset link",
     *     requestBody={"$ref": "#/components/requestBodies/ForgotPasswordRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = $this->userPasswordService->sendResetLink($request->only('email'));

        return $status === true
            ? $this->successResponse([], __('passwords.sent'))
            : $this->errorResponse(__('passwords.user'), 404);
    }

    /**
     * @OA\Post(
     *     path="/password/reset",
     *     tags={"User Password"},
     *     summary="Reset the user password",
     *     requestBody={"$ref": "#/components/requestBodies/ResetPasswordRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid token",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->userPasswordService->resetPassword($request->validated());

        return $status === true
            ? $this->successResponse([], __('passwords.reset'))
            : $this->errorResponse(__('passwords.token'), 400);
    }

    /**
     * @OA\Put(
     *     path="/password/update",
     *     tags={"User Password"},
     *     summary="Update the authenticated user's password",
     *     requestBody={"$ref": "#/components/requestBodies/UpdatePasswordRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Current password is incorrect",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $this->userPasswordService->updatePassword($request->user(), $request->validated());

            return $this->successResponse([], __('passwords.updated'));
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
