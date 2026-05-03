<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            $result = $this->userService->createAdmin($request->only('name', 'email'));

            return response()->json([
                'status' => true,
                'message' => 'Admin user created successfully',
                'password' => $result['password']
            ], 201);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create admin'], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ], [
            'new_password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        try {
            $this->userService->changePassword($request->user(), $request->current_password, $request->new_password);

            return response()->json(['status' => true, 'message' => 'Password changed successfully']);

        } catch (Exception $e) {
            $code = $e->getCode();
            $statusCode = ($code >= 400 && $code < 500) ? $code : 400;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $statusCode);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $newStatus = $this->userService->toggleStatus($id);

            if (!$newStatus) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Status updated!',
                'new_status' => $newStatus,
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function getAdmins(Request $request)
    {
        try {
            $admins = $this->userService->getAdmins();

            return response()->json([
                'status' => true,
                'message' => 'Admin users retrieved successfully',
                'data' => $admins,
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve admins'], 500);
        }
    }
}
