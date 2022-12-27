<?php

namespace App\Services;

use App\Enum\UserRoleEnum;
use App\Repositories\User\UserRepository;

class UserService
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
     * Get paginated with filters
     */
    public function getPaginatedWithFilters(array $data): array
    {
        $baseQuery  = $this->userRepository->where('role', UserRoleEnum::USER);
        $query      = clone $baseQuery;

        if ($value = $data['search']) {
            $query->where(function ($query) use ($value) {
                $query->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('identification', 'like', "%{$value}%");
            });
        }

        $total  = $query->count();

        $users  = $query->with('city')
                	->orderBy($data['sort'], $data['order'])
                    ->offset($data['offset'])->limit($data['limit'])
                    ->get();

        return [
            'rows'              => $users,
            'total'             => $total,
            'totalNotFiltered'  => $baseQuery->count(),
        ];
    }

    /**
     * Create User
     */
    public function store(array $data): array
    {
        return $this->userRepository->create($data + [
            'role'              => UserRoleEnum::USER,
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create Token
     */
    public function createToken(array $user): string
    {
        // @TODO
        // $user->createToken('claroinsurance_app')->accessToken;
        return '';
    }

    /**
     * Delete Tokens
     */
    public function deleteTokens(array $data): void
    {
        // @TODO
    }

    /**
     * Get User
     */
    public function getById(int $id): array
    {
        return $this->userRepository->getById($id);
    }

    /**
     * Update User
     */
    public function update(int $id, array $data): array
    {
        return $this->userRepository->updateById($id, $data);
    }

    /**
     * Delete User
     */
    public function deleteById($id): bool
    {
        return $this->userRepository->deleteById($id);
    }
}
