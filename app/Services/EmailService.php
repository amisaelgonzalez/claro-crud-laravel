<?php

namespace App\Services;

use App\Enum\EmailStatusEnum;
use App\Repositories\Eloquent\Email\EmailRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class EmailService
{
    protected EmailRepository $emailRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    /**
     * Get paginated by user
     */
    public function getPaginatedByUser(int $offset, int $limit): array
    {
        $query = $this->emailRepository->where('user_id', Auth::id());

        $total  = $query->count();
        $emails = $query->offset($offset)->limit($limit)->get();

        return [
            'rows'              => $emails,
            'total'             => $total,
            'totalNotFiltered'  => $total,
        ];
    }

    /**
     * Get emails pending
     */
    public function getPending(): array
    {
        return $this->emailRepository->where('status', 'pending')->get();
    }

    /**
     * Create Email
     */
    public function store(array $data): array
    {
        return $this->emailRepository->create($data + [
            'status'    => EmailStatusEnum::PENDING,
            'user_id'   => Auth::id(),
        ]);
    }

    /**
     * Get Email by id
     */
    public function getById(int $id): array
    {
        return $this->emailRepository->getById($id);
    }

    /**
     * Get Email by id and status
     */
    public function getByIdAndStatus(int $id, ?string $status): array
    {
        $email = $this->emailRepository->getById($id);

        if ($status && $status !== $email['status']) {
            throw new ModelNotFoundException();
        }

        return $email;
    }

    /**
     * Update Email
     */
    public function update(int $id, array $data): array
    {
        return $this->emailRepository->updateById($id, $data);
    }

    /**
     * Update Email to sent
     */
    public function updateToSent(int $id): array
    {
        return $this->emailRepository->updateById($id, ['status' => EmailStatusEnum::SENT]);
    }

    /**
     * Delete Email
     */
    public function deleteById($id): bool
    {
        return $this->emailRepository->deleteById($id);
    }
}
