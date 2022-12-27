<?php

namespace App\Services;

use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    protected UserRepository $userRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Update user
     */
    public function update(array $data): array
    {
        return $this->userRepository->updateById(Auth::id(), $data);
    }

    /**
     * User delete account
     */
    public function deleteAccount(): bool
    {
        return $this->userRepository->deleteById(Auth::id());

        // Send email
        // Delete tokens

        // $this->userRepository->deleteTokens($user['id']);
    }
}
