<?php

namespace App\Http\Controllers;

use App\Facades\AuthJwtGuard;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   operationId="Login",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string"),
     *       @OA\Property(property="password", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success","message"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object",
     *         required={"token","expires_at"},
     *         @OA\Property(
     *           property="token", type="string",
     *           example="51|AIq61yLOTpEtTbywZ9Ba5MKapRlgrboyj3j2RXDFdf041a06"
     *         ),
     *         @OA\Property(property="expires_at", ref="#/components/schemas/typeTimestampNullable")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(
     *     response="422",
     *     description="Validation error",
     *     @OA\JsonContent(ref="#/components/schemas/validationError")
     *   )
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
            ],
            'password' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $token = $this->authService->login(
            $request->input('username'),
            $request->input('password')
        );

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *   path="/api/auth/logout",
     *   operationId="Logout",
     *   tags={"Auth"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true)
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized")
     * )
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->success();
    }

    /**
     * @OA\Post(
     *   path="/api/auth/refresh",
     *   operationId="Refresh",
     *   tags={"Auth"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success","message"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object",
     *         required={"token","expires_at"},
     *         @OA\Property(
     *           property="token", type="string",
     *           example="51|AIq61yLOTpEtTbywZ9Ba5MKapRlgrboyj3j2RXDFdf041a06"
     *         ),
     *         @OA\Property(property="expires_at", ref="#/components/schemas/typeTimestampNullable")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized")
     * )
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken($this->authService->refresh());
    }

    /**
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->success([
            'token' => $token,
            'expires_at' => now()->addMinutes(AuthJwtGuard::getTtl()),
        ]);
    }
}
