<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            return ['status' => false, 'message' => 'Invalid login details', 'code' => 401];
        }

        if ($user->status !== 'active') {
            return [
                'status' => false,
                'message' => 'Your account is inactive. Please contact the administrator.',
                'code' => 403
            ];
        }

        // Check lock status
        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            $minutesLeft = now()->diffInMinutes($user->locked_until);
            return [
                'status' => false,
                'message' => "Account is locked due to too many failed login attempts. Try again in {$minutesLeft} minutes.",
                'code' => 423
            ];
        }

        // Reset lock if expired
        if ($user->locked_until && now()->greaterThanOrEqualTo($user->locked_until)) {
            $this->userRepository->update($user->id, ['login_attempts' => 0, 'locked_until' => null]);
            $user->refresh();
        }

        if (!Auth::attempt($credentials)) {
            $user->increment('login_attempts');

            if ($user->login_attempts >= 5) {
                $this->userRepository->update($user->id, ['locked_until' => now()->addMinutes(15)]);
                return [
                    'status' => false,
                    'message' => 'Account locked due to too many failed login attempts. Try again in 15 minutes.',
                    'code' => 423
                ];
            }

            $attemptsLeft = 5 - $user->login_attempts;
            return [
                'status' => false,
                'message' => "Invalid login details. {$attemptsLeft} attempts remaining before account lockout.",
                'code' => 401
            ];
        }

        // Successful login
        $this->userRepository->update($user->id, ['login_attempts' => 0, 'locked_until' => null]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
            'code' => 200
        ];
    }

    public function logout($user)
    {
        if ($user) {
            $user->currentAccessToken()->delete();
        }
        return ['status' => true, 'message' => 'Logged out'];
    }
}
