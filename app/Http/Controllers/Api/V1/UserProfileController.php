<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Common\BaseController;
use App\Http\Requests\Api\V1\Auth\UpdatePictureRequest;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Api\V1\UserProfileService;
use Illuminate\Http\Request;

class UserProfileController extends BaseController
{
    protected $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    /**
     * @OA\Put(
     *     path="/profile",
     *     tags={"User Profile"},
     *     summary="Update the authenticated user's profile information",
     *     requestBody={"$ref": "#/components/requestBodies/UpdateProfileRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *
     *         @OA\JsonContent(
     *             allOf={
     *
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     type="object",
     *
     *                     @OA\Property(property="data", type="object",
     *                         @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userProfileService->updateProfile($request->user(), $request->validated());

        return $this->successResponse([
            'user' => new UserResource($user),
        ], __('auth.profile_updated'));
    }

    /**
     * @OA\Put(
     *     path="/profile/picture",
     *     tags={"User Profile"},
     *     summary="Update the authenticated user's profile picture",
     *     requestBody={"$ref": "#/components/requestBodies/UpdatePictureRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile picture updated successfully",
     *
     *         @OA\JsonContent(
     *             allOf={
     *
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     type="object",
     *
     *                     @OA\Property(property="data", type="object",
     *                         @OA\Property(property="picture_url", type="string", example="/storage/user/profile_pictures/1.jpg")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="No picture uploaded",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function updatePicture(UpdatePictureRequest $request)
    {
        $result = $this->userProfileService->updatePicture($request->user(), $request);

        if ($result['success']) {
            return $this->successResponse([
                'picture_url' => $result['picture_url'],
            ], __('auth.picture_updated'));
        }

        return $this->errorResponse(__('auth.no_picture_uploaded'), 400);
    }

    /**
     * @OA\Delete(
     *     path="/",
     *     tags={"User Profile"},
     *     summary="Delete the authenticated user's account",
     *     description="This endpoint allows an authenticated user to delete their account permanently.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Account deleted successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Error deleting account",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function deleteAccount(Request $request)
    {
        $deleted = $this->userProfileService->deleteAccount($request->user());

        return $deleted
            ? $this->successResponse([], __('auth.account_deleted'))
            : $this->errorResponse(__('auth.account_delete_failed'), 400);
    }
}
