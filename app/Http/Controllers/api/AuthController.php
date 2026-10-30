<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *   path="/auth/login",
     *   tags={"Authentication"},
     *   summary="User login",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email"),
     *       @OA\Property(property="password", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Login successful"),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result['status']) {
            return response()->json([
                'status' => false,  
                'message' => "Invalid credentials"]
            );
        }

        $response = response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $result['token'],
        ], 200);

        // Cookie logic could also be in service but often controller specific due to request context
        // Keeping basic cookie logic here or moving to service method "createAuthCookie"
        // For minimal controller, let's keep it here as it touches Response object heavily

        $token = $result['token'];
        $origin = $request->header('Origin') ?? $request->header('Referer') ?? '';
        $requestHost = $request->getHost();
        $requestScheme = $request->getScheme();

        $isCrossOrigin = $request->header('Origin') && $request->header('Origin') !== $request->getSchemeAndHttpHost();
        $isHttps = $request->isSecure() || $requestScheme === 'https' || str_starts_with(config('app.url'), 'https://');

        $sameSite = $isCrossOrigin ? 'none' : 'lax';
        $secure = $isCrossOrigin ? true : $isHttps;

        $response->cookie(
            'auth_token',
            $token,
            60 * 24 * 7,
            '/',
            null,
            $secure,
            true,
            false,
            $sameSite
        );

        return $response;
    }


    /**
     * @OA\Get(
     *   path="/auth/logout",
     *   tags={"Authentication"},
     *   summary="User logout",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Logout successful"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $result = $this->authService->logout($request->user());

        return response()->json($result);
    }

    /**
     * @OA\Get(
     *   path="/user",
     *   tags={"Authentication"},
     *   summary="Get current user info",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Response(response=200, description="Current user information")
     * )
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'role' => $request->user()->role
        ]);
    }
}