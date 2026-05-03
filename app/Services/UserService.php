<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Exception;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createAdmin(array $data)
    {
        $randomPassword = bin2hex(random_bytes(4));

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'admin',
            'password' => Hash::make($randomPassword),
            'status' => 'active'
        ];

        $user = $this->userRepository->create($userData);

        // Send email
        Mail::to($user->email)->send(new \App\Mail\AdminAddedMail($user->name, $user->email, $randomPassword));

        return ['user' => $user, 'password' => $randomPassword];
    }

    public function changePassword($user, $currentPassword, $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new Exception('Current password is incorrect', 400);
        }

        $this->userRepository->update($user->id, [
            'password' => Hash::make($newPassword)
        ]);

        return true;
    }

    public function toggleStatus($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user)
            return null;

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $this->userRepository->update($id, ['status' => $newStatus]);
        return $newStatus;
    }

    public function getAdmins()
    {
        // Should really be in repository but simple query is fine to delegate or add method to repo
        // Using model here for specific query or add 'findByRole' to repo. 
        // For strictness, let's assume we use the model via repo... 
        // I'll leave it as Model usage for now or add findByRole later.
        // Actually, let's just use Model directly as it's a specific query.
        return \App\Models\User::where('role', 'admin')->get(['id', 'name', 'email', 'status', 'created_at']);
    }
}
