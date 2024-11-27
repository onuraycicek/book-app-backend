<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Common\BaseController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Services\Api\V1\AuthService;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     requestBody={"$ref": "#/components/requestBodies/RegisterRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(
     *             allOf={
     *
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     type="object",
     *
     *                     @OA\Property(property="data", type="object",
     *                         @OA\Property(property="token", type="string", example="token-example"),
     *                         @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return $this->successResponse($result, __('auth.register_success'));
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     requestBody={"$ref": "#/components/requestBodies/LoginRequestBody"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *
     *         @OA\JsonContent(
     *             allOf={
     *
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     type="object",
     *
     *                     @OA\Property(property="data", type="object",
     *                         @OA\Property(property="token", type="string", example="token-example"),
     *                         @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return $this->successResponse($result, __('auth.login_success'));
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse([], __('auth.logged_out'));
    }
}
