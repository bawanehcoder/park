<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    use ApiResponses;
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login user and generate a token",
     *     description="Authenticates a user using email and password and returns an authentication token.",
     *     operationId="loginUser",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", description="The user's email address",example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", format="password", description="The user's password",example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login with token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", description="Authentication token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials or missing fields"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - invalid credentials"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = User::whereEmail($request->email)->firstOrFail();

        $token = $user->createToken('auth-token');

        $data = [
            'token' => $token->plainTextToken,
            'user' => new UserResource($user),

        ];

        return $this->successResponse($data);
    }
}
